import time
import requests
import RPi.GPIO as GPIO

PIN = 26

COIN_ENDPOINT = "http://127.0.0.1:8000/coin"

PULSE_GAP = 0.35

GPIO.setmode(GPIO.BCM)

GPIO.setup(
    PIN,
    GPIO.IN,
    pull_up_down=GPIO.PUD_UP
)

pulse_count = 0

last_pulse_time = 0

last_state = GPIO.input(PIN)


def amount_from_pulses(pulses):
    PULSE_MAP = {
        1: 1,
        5: 5,
        10: 10,
        20: 20,
    }

    return PULSE_MAP.get(pulses, 0)


def send_coin(amount):
    try:
        response = requests.post(
            COIN_ENDPOINT,
            json={"amount": amount},
            timeout=2,
        )

        print(
            f"Sent ₱{amount} | "
            f"Status: {response.status_code} | "
            f"{response.text}"
        )

    except Exception as error:
        print(
            f"Failed to send ₱{amount}: {error}"
        )


print("Coin listener active on GPIO26")
print("Pulse mapping:")
print("1 pulse  = ₱1")
print("5 pulses = ₱5")
print("10 pulses = ₱10")
print("20 pulses = ₱20")
print("Waiting for coin pulses...\n")

try:
    while True:
        current_state = GPIO.input(PIN)

        now = time.time()

        if (
            last_state == GPIO.HIGH and
            current_state == GPIO.LOW
        ):
            pulse_count += 1

            last_pulse_time = now

            print(
                f"Pulse detected | "
                f"Current pulses: {pulse_count}"
            )

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
                    f"Amount: ₱{amount}"
                )

                send_coin(amount)

            else:
                print(
                    f"Unknown pulse count: "
                    f"{pulse_count}"
                )

            pulse_count = 0

        last_state = current_state

        time.sleep(0.001)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
