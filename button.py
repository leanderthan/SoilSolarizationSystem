
import RPi.GPIO as GPIO
import time
import os
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
pre = "PRE"
dur = "DUR"
post = "POST"

sql = "SELECT recordType FROM solsys ORDER BY recordDate DESC LIMIT 1"
mycursor.execute(sql)
typ = mycursor.fetchall()
if mycursor.rowcount == 0:
    print('PRE')
    #GPIO.output(buzzer,GPIO.HIGH)
    time.sleep(5.0) # Delay in seconds
    GPIO.output(buzzer,GPIO.LOW)
    os.system('sudo python /home/pi/Desktop/pre.py')
elif mycursor.rowcount != 0:
    mess = []
    for row in typ:
        mess.append(row[0])
        r = mess[0]
        if r == dur:
            qry = "SELECT COUNT(*) FROM solsys WHERE recordType = 'DUR'"
            mycursor.execute(qry)
            out = []
            put = mycursor.fetchall()
            for row in put:
                out.append(row[0])
                num = out[0]
                if num >= 56
                    print(num)
                    print('POST')
                    GPIO.output(buzzer,GPIO.HIGH)
                    time.sleep(5.0) # Delay in seconds
                    GPIO.output(buzzer,GPIO.LOW)
                    os.system('sudo python /home/pi/Desktop/post.py')
                else:
                    print('DUR')
                    GPIO.output(buzzer,GPIO.HIGH)
                    time.sleep(5.0) # Delay in seconds
                    GPIO.output(buzzer,GPIO.LOW)
                    os.system('sudo python /home/pi/Desktop/dur.py')
        elif r == pre:
            GPIO.output(buzzer,GPIO.HIGH)
            time.sleep(5.0) # Delay in seconds
            GPIO.output(buzzer,GPIO.LOW)
            print('DUR')
            os.system('sudo python /home/pi/Desktop/dur.py')
        elif r == post:
            print('START')
            GPIO.output(buzzer,GPIO.HIGH)
            time.sleep(5.0) # Delay in seconds
            GPIO.output(buzzer,GPIO.LOW)
            os.system('sudo python /home/pi/Desktop/buzzer.py')

