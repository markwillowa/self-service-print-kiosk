import RPi.GPIO as GPIO
import time

PIN = 26

GPIO.setmode(GPIO.BCM)

GPIO.setup(
    PIN,
    GPIO.IN,
    pull_up_down=GPIO.PUD_UP
)

pulse_count = 0

last_state = GPIO.input(PIN)

print("Insert coins now...")

try:
    while True:
        current_state = GPIO.input(PIN)

        if (
            last_state == GPIO.HIGH and
            current_state == GPIO.LOW
        ):
            pulse_count += 1

            print(f"Pulse #{pulse_count}")

        last_state = current_state

        time.sleep(0.001)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
