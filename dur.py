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

message = "DUR"

def job():
	ser = serial.Serial('/dev/ttyACM0', 9600)
	time.sleep(1.0)
	try:
		pr = ser.readline()
		data = pr.split(',',5)
		num = data[0]
		temp = data[1]
		moist = data[2]
		percent = data[3]
		state = data[4]
	except IndexError:
		mr = ser.readline()
		dat = mr.split(',',5)
		num = dat[0]
		temp = dat[1]
		moist = dat[2]
		percent = dat[3]
		state = dat[4]
	dt = datetime.datetime.now()
	dt.strftime('%Y-%m-%d %H:%M:%S')
	sql = "INSERT INTO solsys (recordDate, recordType, temperature, moisture) VALUES (%s, %s, %s, %s)"
	val = (dt, message, temp, percent)
	
	mycursor.execute(sql,val)
	mydb.commit()

	
	GPIO.output(buzzer,GPIO.HIGH)
	time.sleep(1.0) # Delay in seconds
	GPIO.output(buzzer,GPIO.LOW)
	time.sleep(0.25)
	GPIO.output(buzzer,GPIO.HIGH)
	time.sleep(1.0) # Delay in seconds
	GPIO.output(buzzer,GPIO.LOW)
	time.sleep(0.25)
	GPIO.output(buzzer,GPIO.HIGH)
	time.sleep(1.0) # Delay in seconds
	GPIO.output(buzzer,GPIO.LOW)
	time.sleep(0.25)
	
	
schedule.every(6).hours.do(job)

while True:
	schedule.run_pending()
	time.sleep(1.0)
	sql = "SELECT COUNT(*) FROM solsys WHERE recordType = 'DUR'"
	mycursor.execute(sql)
	out = []
	for row in mycursor:
		out.append(row[0])

	num = out[0]
	
	if num >= 56:
		print(num)
		print('POST')
		GPIO.output(buzzer,GPIO.HIGH)
		time.sleep(5.0) # Delay in seconds
		GPIO.output(buzzer,GPIO.LOW)
		time.sleep(0.25)
		os.system('sudo python /home/pi/Desktop/post.py')
	

