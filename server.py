from flask import Flask, request
import cv2
import numpy as np

# Ensure it's always a list
if len(out_layers.shape) == 0:  # If it's a scalar, convert to list
    out_layers = [out_layers]

output_layers = [layer_names[i - 1] for i in out_layers.flatten()]

app = Flask(_name_)

# SAVE_PATH = "latest.jpg"

@app.route(esp_library.php)
def upload_image():
    image_data = request.data  # Get image from ESP32-CAM
    image_array = np.frombuffer(image_data, np.uint8)
    image = cv2.imdecode(image_array, cv2.IMREAD_COLOR)
    
    # Process Image (AI Detection)
    traffic_density = detect_traffic(image)
    print("Traffic Density:", traffic_density)
    
    return traffic_density

def detect_traffic(image):
    height, width, channels = image.shape
    blob = cv2.dnn.blobFromImage(image, 0.00392, (416, 416), swapRB=True, crop=False)
    
    net.setInput(blob)
    detections = net.forward(output_layers)

    vehicle_count = 0
    for detection in detections:
        for obj in detection:
            scores = obj[5:]
            class_id = np.argmax(scores)
            confidence = scores[class_id]
            if confidence > 0.5 and class_id in [2, 3, 5, 7]:  # Car, bus, truck
                vehicle_count += 1

    print("Vehicle count =",vehicle_count)
    if vehicle_count < 3:
        return 'Low'
    elif vehicle_count < 10:
        return 'Medium'
    else:
        return 'High'

@app.route('/')
def home():
    return "Hello, World!"

# if _name_ == '_main_':
app.run(host='0.0.0.0', port=5000, debug=True)
