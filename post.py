
import RPi.GPIO as GPIO
import time
import os
import datetime
import serial
import mysql.connector
import schedule

#GPIO.setwarnings(False)

gpio_pin_number1=17
gpio_pin_number2=27
GPIO.setmode(GPIO.BCM)

GPIO.setup(gpio_pin_number1, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(gpio_pin_number2, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

buzzer=21
GPIO.setup(buzzer,GPIO.OUT)

mydb = mysql.connector.connect(
	host="localhost",
	user="admin",
	passwd="raspberry",
	database="solarization"
)

mycursor = mydb.cursor()

while  True:
	
	input_state = GPIO.input(gpio_pin_number1)
	in_state = GPIO.input(gpio_pin_number2)
	if in_state == True:
		GPIO.output(buzzer,GPIO.HIGH)
		time.sleep(5.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		time.sleep(0.25)
		print('START')
		os.system('sudo python /home/pi/Desktop/buzzer.py')
	if input_state == True:  
		ser = serial.Serial('/dev/ttyACM0', 9600)
		time.sleep(1.0)
		pr = ser.readline()		

		message = "POST"
		data = pr.split(',',7)
		val = data[0]
		temp = data[1]
		moist = data[2]
		percent = data[3]
		state = data[4]
		ec = data[5]
		ph = data[6]
		
		dt = datetime.datetime.now()
		dt.strftime('%Y-%m-%d %H:%M:%S')

		acidz = 0.00
		acidmax = 5.50
		basic = 7.00
			
		if ph >= acidz and  ph < acidmax:
			soilStat = "ACID"
		elif ph >= acidmax and ph <= basic:
			soilStat = "NEUTRAL"
		elif ph > basic:
			soilStat = "BASIC/ALKALINE"
	
		nsalmin = 0.00
		nsalmax = 2.00
		ssal = 4.00
		msal = 8.00
		stsal = 16.00
		
		
		if ec >= nsalmin and ec <= nsalmax:
			salClass = "VSTRONG SALINE"
		elif ec > nsalmax and ec <= ssal:
			salClass = "STRONG SALINE"
		elif ec > ssal and ec <= msal:
			salClass = "MOD SALINE"
		elif ec > msal and ec <= stsal:
			salClass = "SLIGHT SALINE"
		elif ec > stsal:
			salClass = "NON-SALINE"
		
		nsalo = 1.00
		nsalt = 3.00
		if ph >= acidmax and ph <= basic and ec >= nsalo and ec <= nsalt:
			remarks = "OPTIMAL PH/EC RANGE"
		elif ph < acidmax or ph > basic or ec < nsalo or ec > nsalt:
			remarks = "NON-OPTIMAL PH/EC RANGE"
			
		sql = "INSERT INTO solsys (recordDate, recordType, temperature, moisture, ph, soilStatus, ec, salClass, remarks) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
		val = (dt, message, temp, percent, ph, soilStat, ec, salClass,remarks)

		mycursor.execute(sql,val)
		mydb.commit()

		#GPIO.output(buzzer,GPIO.HIGH)
		time.sleep(1.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		time.sleep(0.25)
		#GPIO.output(buzzer,GPIO.HIGH)
		time.sleep(1.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		time.sleep(0.25)
		#GPIO.output(buzzer,GPIO.HIGH)
		time.sleep(1.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		time.sleep(0.25)
