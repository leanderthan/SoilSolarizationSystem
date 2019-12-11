#include <Wire.h>
#include <LCD.h>
#include <LiquidCrystal_I2C.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <DFRobot_EC.h>
#include <EEPROM.h>

LiquidCrystal_I2C lcd(0x27,2,1,0,4,5,6,7);

#define ONE_WIRE_BUS 5
#define EC_PIN A1
#define soilMoistSens A0

#define PHSensorPin  A2    //dissolved oxygen sensor analog output pin to arduino mainboard
#define VREF 5.0    //for arduino uno, the ADC reference is the AVCC, that is 5.0V(TYP)
#define OFFSET 0.00  //zero drift compensation

#define SCOUNT  30           // sum of sample point

#define LDR A3

int LDR_Value;
int RLY = 4;

int analogBuffer[SCOUNT];    //store the analog value in the array, readed from ADC
int analogBufferTemp[SCOUNT];
int analogBufferIndex = 0,copyIndex = 0;

float averageVoltage,phValue;

int btnPin = 3;
boolean State;
boolean BTN_Last;
boolean BTN_Current;

OneWire oneWire (ONE_WIRE_BUS);

DallasTemperature tempSens (&oneWire);

float voltage, ecValue, tempC = 0;

DFRobot_EC ec;

void setup() {
   Serial.begin(9600);
   
   lcd.begin (20,4); // 20 x 4 LCD module
   lcd.setBacklightPin(3,POSITIVE); // BL, BL_POL
   lcd.setBacklight(HIGH); 

   tempSens.begin();
   ec.begin();

   pinMode(btnPin, INPUT);
   attachInterrupt(digitalPinToInterrupt(btnPin), blink, RISING);
   
   pinMode(LDR, INPUT);
   pinMode(RLY, OUTPUT);
}
void loop() {

  // TEMP SENSOR
  
  tempSens.requestTemperatures();
  tempC = tempSens.getTempCByIndex(0);

  // SOIL MOISTURE SENSOR

  int soilMoistVal = analogRead(soilMoistSens);
  int soilPercent = map(soilMoistVal, 600, 315, 0, 100);
  String soilState;
  
  if (soilMoistVal >=260 && soilMoistVal <=350) {
    soilState = " (Water)";
  } else if (soilMoistVal >=351 && soilMoistVal <=430) {
    soilState = " (Wet)";
  } else if (soilMoistVal >=431) {
    soilState = " (Dry)";
  }

  // EC SENSOR
  
  static unsigned long timepoint = millis();
  if(millis()-timepoint>1000U) {
    timepoint = millis();
    voltage = analogRead(EC_PIN)/1024.0*5000;  // read the voltage
    ecValue =  ec.readEC(voltage,tempC);  // convert voltage to EC with temperature compensation
  }
  ec.calibration(voltage,tempC);  // calibration process by Serail CMD
  

  // PH SENSOR

  static unsigned long analogSampleTimepoint = millis();
   if(millis()-analogSampleTimepoint > 30U) {
     analogSampleTimepoint = millis();
     analogBuffer[analogBufferIndex] = analogRead(PHSensorPin);    //read the analog value and store into the buffer
     analogBufferIndex++;
     if(analogBufferIndex == SCOUNT)
         analogBufferIndex = 0;
   }
  static unsigned long printTimepoint = millis();  
  if(millis()-printTimepoint > 1000U) {
     printTimepoint = millis();
     for(copyIndex=0;copyIndex<SCOUNT;copyIndex++)
     {
       analogBufferTemp[copyIndex]= analogBuffer[copyIndex];
     }
     averageVoltage = getMedianNum(analogBufferTemp,SCOUNT) * (float)VREF / 1024.0; // read the value more stable by the median filtering algorithm
     phValue = 3.5 * averageVoltage + OFFSET;  
  }
  
  // LDR and RELAY
  
  LDR_Value = analogRead(LDR);


  if(LDR_Value > 670)
  {
    digitalWrite(RLY, HIGH);
  }
  
  else
  {
    digitalWrite(RLY, LOW); 
  }
  
  // LCD
  
  lcd.setCursor(0,0);
  lcd.print("Temperature: ");
  lcd.print(tempC);
  lcd.print(" C");
  
  lcd.setCursor(0,1);
  lcd.print("Moisture: ");
  lcd.print(soilPercent);
  lcd.print("%");
  lcd.print(soilState);
  lcd.print("     ");
  
  lcd.setCursor(0,2);
  lcd.print("EC: ");
  if(State == 0) {
    lcd.print(ecValue);
    lcd.print(" mS/cm   ");
  } else {
    lcd.print("N/A          ");
    //lcd.print(LDR_Value);
    //lcd.print("             ");
  }
  
  lcd.setCursor(0,3);
  lcd.print("pH: ");
  if(State == 0) {
    lcd.print(phValue);
  } else {
    lcd.print("N/A         ");
  }

  // DISPLAY VALUES IN SERIAL MONITOR
  int num = 1;
  String values;
  values = String(num) + "," + String(tempC) + "," + String(soilMoistVal) + "," + 
            String(soilPercent) + "," + soilState + "," + String(ecValue) + "," + String(phValue);
  Serial.println(values);
}


// FOR PH SENSOR
int getMedianNum(int bArray[], int iFilterLen)
{
      int bTab[iFilterLen];
      for (byte i = 0; i<iFilterLen; i++)
      {
      bTab[i] = bArray[i];
      }
      int i, j, bTemp;
      for (j = 0; j < iFilterLen - 1; j++)
      {
      for (i = 0; i < iFilterLen - j - 1; i++)
          {
        if (bTab[i] > bTab[i + 1])
            {
        bTemp = bTab[i];
            bTab[i] = bTab[i + 1];
        bTab[i + 1] = bTemp;
         }
      }
      }
      if ((iFilterLen & 1) > 0)
    bTemp = bTab[(iFilterLen - 1) / 2];
      else
    bTemp = (bTab[iFilterLen / 2] + bTab[iFilterLen / 2 - 1]) / 2;
      return bTemp;
}

void blink(){
  State = !State;
}