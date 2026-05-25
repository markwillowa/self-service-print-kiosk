import time
import requests
import RPi.GPIO as GPIO

PIN = 26

COIN_ENDPOINT = "http://10.42.0.1:8000/coin"

PULSE_GAP = 0.45
DEBOUNCE_TIME = 0.035
MIN_PULSE_LOW_TIME = 0.01
COIN_COOLDOWN = 0.8
STARTUP_DELAY = 2

PULSE_MAP = {
    1: 1,
    5: 5,
    10: 10,
    20: 20,
}

GPIO.setmode(GPIO.BCM)

GPIO.setup(
    PIN,
    GPIO.IN,
    pull_up_down=GPIO.PUD_UP,
)

pulse_count = 0
last_pulse_time = 0
last_valid_edge_time = 0
last_coin_sent_time = 0
low_started_at = None

last_state = GPIO.input(PIN)


def amount_from_pulses(pulses):
    return PULSE_MAP.get(pulses, 0)


def send_credit_pulses(amount):
    for credit in range(amount):
        try:
            response = requests.post(
                COIN_ENDPOINT,
                json={"amount": 1},
                timeout=2,
            )

            print(
                f"Sent credit {credit + 1}/{amount} | "
                f"Status: {response.status_code}"
            )

            time.sleep(0.15)

        except Exception as error:
            print(
                f"Failed to send credit: {error}"
            )


print("Coin listener active on GPIO26")
print("Waiting for GPIO to settle...")

time.sleep(STARTUP_DELAY)

last_state = GPIO.input(PIN)

print("Ready.")
print("Pulse mapping:")
print("1 pulse  = PHP 1")
print("5 pulses = PHP 5")
print("10 pulses = PHP 10")
print("20 pulses = PHP 20")
print("Waiting for coin pulses...\n")

try:
    while True:
        current_state = GPIO.input(PIN)

        now = time.time()

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
                    now - last_valid_edge_time >= DEBOUNCE_TIME and
                    now - last_coin_sent_time >= COIN_COOLDOWN
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
            amount = amount_from_pulses(
                pulse_count
            )

            if amount > 0:
                print(
                    f"Coin complete | "
                    f"Pulses: {pulse_count} | "
                    f"Amount: PHP {amount}"
                )

                send_credit_pulses(amount)

                last_coin_sent_time = now

            else:
                print(
                    f"Unknown pulse count: "
                    f"{pulse_count}"
                )

            pulse_count = 0

            low_started_at = None

        last_state = current_state

        time.sleep(0.002)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
