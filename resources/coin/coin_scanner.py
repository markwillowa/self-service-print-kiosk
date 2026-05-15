import RPi.GPIO as GPIO
import time
from datetime import datetime

GPIO.setmode(GPIO.BCM)

IGNORE_PINS = {
    0, 1, 2, 3, 4,
    14, 15,
}

PINS = []

for pin in range(2, 28):
    if pin not in IGNORE_PINS:
        PINS.append(pin)

for pin in PINS:
    GPIO.setup(pin, GPIO.IN, pull_up_down=GPIO.PUD_UP)

last_state = {
    pin: GPIO.input(pin)
    for pin in PINS
}

print("Scanning ALL GPIO pins...")
print("Insert coins now.")
print("Press CTRL+C to stop.\n")

try:
    while True:
        for pin in PINS:
            current_state = GPIO.input(pin)

            if (
                last_state[pin] == GPIO.HIGH and
                current_state == GPIO.LOW
            ):
                print(
                    f"[{datetime.now().strftime('%H:%M:%S.%f')[:-3]}] "
                    f"PULSE DETECTED ON GPIO{pin}"
                )

            last_state[pin] = current_state

        time.sleep(0.001)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
