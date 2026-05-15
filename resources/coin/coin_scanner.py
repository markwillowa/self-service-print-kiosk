import RPi.GPIO as GPIO
import time

PIN = 19

GPIO.setmode(GPIO.BCM)

GPIO.setup(
    PIN,
    GPIO.IN,
    pull_up_down=GPIO.PUD_UP
)

print(f"Watching GPIO{PIN}...")
print("Press CTRL+C to stop.\n")

try:
    while True:
        state = GPIO.input(PIN)

        print(
            f"GPIO{PIN}: {'HIGH' if state else 'LOW'}",
            end='\r'
        )

        time.sleep(0.05)

except KeyboardInterrupt:
    print("\nStopped.")

finally:
    GPIO.cleanup()
