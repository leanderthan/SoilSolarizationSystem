#Libraries
import RPi.GPIO as GPIO
import time
import os
#Disable warnings (optional)
GPIO.setwarnings(False)

#Select GPIO mode
GPIO.setmode(GPIO.BCM)
gpio_pin_number1=17
gpio_pin_number2=27
GPIO.setmode(GPIO.BCM)

GPIO.setup(gpio_pin_number1, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(gpio_pin_number2, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
buzzer=21
GPIO.setup(buzzer,GPIO.OUT)

print('BUZZ')
GPIO.output(buzzer,GPIO.HIGH)
print ("Beep")
time.sleep(0.5) # Delay in seconds
GPIO.output(buzzer,GPIO.LOW)
print ("No Beep")
time.sleep(0.5)
#Run forever loop
while True:
	input_state = GPIO.input(gpio_pin_number1)
	in_state = GPIO.input(gpio_pin_number2)
	if input_state == True:
		os.system('sudo python /home/pi/Desktop/button.py')
	if in_state == True:
		GPIO.output(buzzer,GPIO.HIGH)
		print ("Beep")
		time.sleep(3.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		print ("No Beep")
