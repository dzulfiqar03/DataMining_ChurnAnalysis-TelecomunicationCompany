from flask import Flask, render_template, request, send_file
import pandas as pd
import joblib

app = Flask(__name__)

# Load model, scaler, selector
model = joblib.load('logistic_model.pkl')
scaler = joblib.load('scaler.pkl')
selector = joblib.load('selector.pkl')
selected_features = selector.get_support(indices=True)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/predict', methods=['POST'])
def predict():
    if 'file' not in request.files:
        return "No file uploaded"
    file = request.files['file']
    df = pd.read_csv(file)

    # Ambil fitur terpilih dan normalisasi
    X = df.iloc[:, selected_features]
    X_scaled = scaler.transform(X)

    # Prediksi
    df['Churn_Predicted'] = model.predict(X_scaled)
    df['Churn_Probability'] = model.predict_proba(X_scaled)[:,1]

    # Export Excel
    output_file = 'Churn_Prediction.xlsx'
    df.to_excel(output_file, index=False)

    return send_file(output_file, as_attachment=True)

if __name__ == '__main__':
    app.run(debug=True)
