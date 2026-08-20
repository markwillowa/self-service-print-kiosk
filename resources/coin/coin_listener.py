import time
import requests
import RPi.GPIO as GPIO

PIN = 26

COIN_ENDPOINT = "http://10.42.0.1:8000/coin"

PULSE_GAP = 0.35
DEBOUNCE_TIME = 0.03
MIN_PULSE_LOW_TIME = 0.008
STARTUP_DELAY = 2
LOOP_SLEEP = 0.001

PULSE_MAP = {
    1: 1,
    2: 2,
    10: 10,
    20: 20,
    5: 5,
}

GPIO.setmode(GPIO.BCM)

GPIO.setup(
    PIN,
    GPIO.IN,
    pull_up_down=GPIO.PUD_UP,
)

session = requests.Session()

pulse_count = 0
last_pulse_time = 0
last_valid_edge_time = 0
low_started_at = None
last_state = GPIO.input(PIN)


def amount_from_pulses(pulses):
    return PULSE_MAP.get(pulses, 0)


def send_credit(amount):
    try:
        response = session.post(
            COIN_ENDPOINT,
            json={"amount": amount},
            timeout=2,
        )

        print(
            f"Credit loaded | Amount: PHP {amount} | "
            f"Status: {response.status_code}"
        )

        return response.status_code == 200

    except Exception as error:
        print(f"Failed to send credit PHP {amount}: {error}")

        return False


print("Coin listener active on GPIO26")
print("Waiting for GPIO to settle...")

time.sleep(STARTUP_DELAY)

last_state = GPIO.input(PIN)

print("Ready.")
print("Pulse mapping:")
print("1 pulse  = PHP 1")
print("2 pulse  = PHP 2")
print("10 pulses = PHP 10")
print("20 pulses = PHP 20")
print("5 pulses = PHP 5")
print("Waiting for coin pulses...\n")

try:
    while True:
        current_state = GPIO.input(PIN)
        now = time.monotonic()

        if (
            current_state == GPIO.LOW and
            last_state == GPIO.HIGH
        ):
            low_started_at = now

        if (
            current_state == GPIO.HIGH and
            last_state == GPIO.LOW
        ):
            if low_started_at is not None:
                low_duration = now - low_started_at

                if (
                    low_duration >= MIN_PULSE_LOW_TIME and
                    now - last_valid_edge_time >= DEBOUNCE_TIME
                ):
                    pulse_count += 1
                    last_pulse_time = now
                    last_valid_edge_time = now

                    print(
                        f"Pulse detected | "
                        f"Current pulses: {pulse_count}"
                    )

            low_started_at = None

        if (
            pulse_count > 0 and
            now - last_pulse_time >= PULSE_GAP
        ):
            amount = amount_from_pulses(pulse_count)

            if amount > 0:
                print(
                    f"Coin complete | "
                    f"Pulses: {pulse_count} | "
                    f"Amount: PHP {amount}"
                )

                send_credit(amount)
            else:
                print(
                    f"Unknown pulse count ignored: {pulse_count}"
                )

            pulse_count = 0
            low_started_at = None

        last_state = current_state

        time.sleep(LOOP_SLEEP)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
