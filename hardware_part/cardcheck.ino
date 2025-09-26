#include <WiFi.h>
#include <HTTPClient.h>
#include <rdm6300.h>
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <ESP32Servo.h>

// WiFi config
const char* ssid = "shouty";
const char* password = "shouty55";

// Server (your XAMPP)
String serverURL = "http://10.46.70.96/toll_system/rfid/ingest.php";

// RFID config
#define RDM6300_RX_PIN 13
Rdm6300 rdm6300;
String CardNumber;

// OLED config
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

// Servo config
Servo gateServo;
#define SERVO_PIN 12
#define BUZZER 14

// Helper: OLED
void showMessage(String l1, String l2 = "") {
  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.setCursor(0, 10);
  display.println(l1);
  display.setCursor(0, 30);
  display.println(l2);
  display.display();
}

// Helper: Buzz
void buzz(int times = 1, int duration = 200) {
  for (int i = 0; i < times; i++) {
    digitalWrite(BUZZER, HIGH);
    delay(duration);
    digitalWrite(BUZZER, LOW);
    delay(duration);
  }
}

// Helper: Open/close gate
void moveGate() {
  for (int pos = 180; pos >= 100; pos--) {
    gateServo.write(pos);
    delay(5);
  }
  delay(3000);
  for (int pos = 100; pos <= 180; pos++) {
    gateServo.write(pos);
    delay(5);
  }
}

void setup() {
  Serial.begin(115200);
  pinMode(BUZZER, OUTPUT);
  digitalWrite(BUZZER, LOW);

  gateServo.attach(SERVO_PIN);
  gateServo.write(180);

  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println("SSD1306 failed");
    while (true);
  }
  showMessage("Starting...", "Toll System");

  // WiFi
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println(" connected!");
  showMessage("WiFi Connected");

  rdm6300.begin(RDM6300_RX_PIN);
  delay(2000);
}

void loop() {
  if (rdm6300.get_new_tag_id()) {
    CardNumber = String(rdm6300.get_tag_id(), DEC);
    Serial.println("Scanned Card: " + CardNumber);
    showMessage("Card Scanned", CardNumber);
    buzz();

    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      http.begin(serverURL);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      String body = "card_uid=" + CardNumber;
      int code = http.POST(body);

      if (code > 0) {
        String res = http.getString();
        Serial.println("Server: " + res);

        // Parse balance if present
        String balanceStr = "";
        int pos = res.indexOf("BALANCE:");
        if (pos > 0) {
          balanceStr = res.substring(pos + 8);
        }

        if (res.startsWith("STATUS:valid")) {
          moveGate();
          showMessage("Access Granted", "Bal: " + balanceStr);
        } 
        else if (res.startsWith("STATUS:emergency")) {
          moveGate();
          showMessage("Emergency", "Free Entry");
        } 
        else if (res.startsWith("STATUS:lost")) {
          buzz(5);
          showMessage("Lost Card!", "Access Denied");
        } 
        else if (res.startsWith("STATUS:invalid")) {
          buzz(3);
          showMessage("Insufficient", "Bal: " + balanceStr);
        } 
        else {
          buzz(2);
          showMessage("Not Registered", res);
        }
      } else {
        Serial.println("HTTP Error: " + String(code));
        showMessage("Server Error");
      }
      http.end();
    } else {
      Serial.println("WiFi Disconnected");
      showMessage("WiFi Lost");
    }

    delay(2000);
    showMessage("Ready", "Scan Again");
  }
}
