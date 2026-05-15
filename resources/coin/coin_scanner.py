import RPi.GPIO as GPIO
import time
from datetime import datetime

PIN = 26

PULSE_GAP = 0.35

GPIO.setmode(GPIO.BCM)
GPIO.setup(PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)

pulse_count = 0
last_pulse_time = 0
last_state = GPIO.input(PIN)


def coin_value_from_pulses(pulses):
    if pulses == 1:
        return 1

    if pulses == 2:
        return 5

    return 0


print("Listening on GPIO26...")
print("1 pulse = ₱1")
print("2 pulses = ₱5")
print("Insert coins now. CTRL+C to stop.\n")

try:
    while True:
        current_state = GPIO.input(PIN)
        now = time.time()

        if last_state == GPIO.HIGH and current_state == GPIO.LOW:
            pulse_count += 1
            last_pulse_time = now

            print(
                f"[{datetime.now().strftime('%H:%M:%S.%f')[:-3]}] "
                f"Pulse detected. Current coin pulses: {pulse_count}"
            )

        if pulse_count > 0 and now - last_pulse_time >= PULSE_GAP:
            amount = coin_value_from_pulses(pulse_count)

            print(
                f"Coin complete: {pulse_count} pulse(s) = ₱{amount}\n"
            )

            pulse_count = 0

        last_state = current_state

        time.sleep(0.001)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
