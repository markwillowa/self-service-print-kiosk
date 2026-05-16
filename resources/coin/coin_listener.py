import time
import requests
import RPi.GPIO as GPIO

PIN = 26
COIN_ENDPOINT = "http://127.0.0.1:8000/coin"

PULSE_GAP = 0.35

GPIO.setmode(GPIO.BCM)
GPIO.setup(PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)

pulse_count = 0
last_pulse_time = 0
last_state = GPIO.input(PIN)


def amount_from_pulses(pulses):
    if pulses == 1:
        return 1

    if pulses == 5:
        return 5

    return 0


def send_coin(amount):
    response = requests.post(
        COIN_ENDPOINT,
        json={"amount": amount},
        timeout=2,
    )

    print(f"Sent ₱{amount}: {response.status_code} {response.text}")


print("Coin listener active on GPIO26")
print("1 pulse = ₱1")
print("5 pulses = ₱5")

try:
    while True:
        current_state = GPIO.input(PIN)
        now = time.time()

        if last_state == GPIO.HIGH and current_state == GPIO.LOW:
            pulse_count += 1
            last_pulse_time = now

            print(f"Pulse detected: {pulse_count}")

        if pulse_count > 0 and now - last_pulse_time >= PULSE_GAP:
            amount = amount_from_pulses(pulse_count)

            if amount > 0:
                send_coin(amount)
            else:
                print(f"Unknown coin pulse count: {pulse_count}")

            pulse_count = 0

        last_state = current_state

        time.sleep(0.001)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
