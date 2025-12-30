import streamlit as st
import numpy as np
import pandas as pd
import joblib

# Load model & scaler
model = joblib.load("logistic_model.pkl")
scaler = joblib.load("scaler.pkl")

st.title("Prediksi Churn Pelanggan")

# Input fitur
tenure = st.number_input("Tenure (bulan)", min_value=0, max_value=100, value=12)

# Pilihan 3 kategori untuk fitur biner + no service
online_security = st.selectbox("OnlineSecurity", ["Yes", "No", "No internet service"])
tech_support = st.selectbox("TechSupport", ["Yes", "No", "No internet service"])

# Mapping sesuai encoding model: 0=No, 1=Yes, 2=No internet service
mapping = {"No": 0, "Yes": 1, "No internet service": 2}
online_security_val = mapping[online_security]
tech_support_val = mapping[tech_support]

# Tombol prediksi
if st.button("Predict"):
    # Buat array input
    features = np.array([[tenure, online_security_val, tech_support_val]])

    # Scaling
    features_scaled = scaler.transform(features)

    # Prediksi
    prediction = model.predict(features_scaled)[0]
    proba = model.predict_proba(features_scaled)[0]

    # Map label
    label_map = {0: "Tidak Churn", 1: "Churn"}
    pred_label = label_map[prediction]

    st.write(f"**Prediksi:** {pred_label}")

    # Probabilitas
    proba_df = pd.DataFrame({
        "Kelas": [label_map[0], label_map[1]],
        "Probabilitas (%)": [proba[0]*100, proba[1]*100]
    })
    st.bar_chart(proba_df.set_index("Kelas"))

    # Hasil lengkap untuk download
    result_df = pd.DataFrame({
        "Tenure": [tenure],
        "OnlineSecurity": [online_security],
        "TechSupport": [tech_support],
        "Prediksi": [pred_label],
        "Probabilitas Tidak Churn (%)": [proba[0]*100],
        "Probabilitas Churn (%)": [proba[1]*100]
    })

    st.write(result_df)