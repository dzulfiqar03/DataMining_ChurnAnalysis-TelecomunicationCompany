import streamlit as st
import numpy as np
import pandas as pd
import joblib
import streamlit.components.v1 as components # Pastikan ini diimport
import requests
# Load model & scaler
model = joblib.load("logistic_model.pkl")
scaler = joblib.load("scaler.pkl")
model_kmeans = joblib.load('kmeans_model.pkl')

st.title("Prediksi Churn Pelanggan")

name = st.text_input("Nama Lengkap")
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
    cluster_hasil = model_kmeans.predict(features_scaled)[0]
    prediction = model.predict(features_scaled)[0]
    proba = model.predict_proba(features_scaled)[0]
    # Map label
    label_map = {0: "Tidak Churn", 1: "Churn"}
    pred_label = label_map[prediction]
    

    # # Probabilitas
    # proba_df = pd.DataFrame({
    #     "Kelas": [label_map[0], label_map[1]],
    #     "Probabilitas (%)": [proba[0]*100, proba[1]*100]
    # })
    # st.bar_chart(proba_df.set_index("Kelas"))

    # # Hasil lengkap untuk download
    # result_df = pd.DataFrame({
    #     "Nama": [name],
    #     "Tenure": [tenure],
    #     "Online Security": [online_security],
    #     "TechSupport": [tech_support],
    #     "Cluster":[cluster_hasil],
    #     "Prediksi": [pred_label],
    #     "Probabilitas Tidak Churn (%)": [proba[0]*100],
    #     "Probabilitas Churn (%)": [proba[1]*100]
    # })

    # st.write(result_df)
    
        
    df_cluster = pd.read_excel('Tubes_Kelompok_PenambanganData.xlsx', sheet_name='K-Means Cluster')
    jumlah_pelanggan = df_cluster['Cluster'].value_counts().sort_index()
    data_cluster = jumlah_pelanggan.to_dict()
    
    payload = {
        "name": name, 
        "tenure": tenure, 
        "online_security": online_security, 
        "tech_support": tech_support,
        "cluster": int(cluster_hasil),
        "predict": pred_label,
        "prob_nochurn": float(proba[0] * 100),
        "prob_churn": float(proba[1] * 100),
        "cluster_summary": data_cluster,
        }
    # Kirim POST request ke endpoint Laravel
    res = requests.post("http://127.0.0.1:8000/api/predicted", json=payload)

    requests.post(
        "http://localhost:8000/api/cluster-summary",
        json=payload
    )
    
    if res.status_code == 201 or res.status_code == 200:
            st.success("Prediksi Berhasil & Dashboard Disinkronkan!")
            
    if res.status_code == 201:
        st.success("Berhasil disimpan via API Laravel!")