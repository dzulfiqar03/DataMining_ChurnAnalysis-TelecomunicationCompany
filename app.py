import streamlit as st
import streamlit.components.v1 as components
import requests
import pandas as pd
import plotly.express as px

st.set_page_config(layout="wide")


url_laravel = "http://127.0.0.1:8000"

# Mengambil data dari API Laravel
response = requests.get("http://127.0.0.1:8000/api/data-sensor")


if response.status_code == 200:
    data_json = response.json()
    # df = pd.DataFrame({
    #     'Bulan': data_json['labels'],
    #     'Nilai': data_json['data']
    # })
    
    # st.line_chart(df.set_index('Bulan'))
    

    # df_plot = jumlah_pelanggan.reset_index()
    # df_plot.columns = ['Cluster', 'Total']
    # # 1. Definisikan Map Warna (Samakan dengan CSS Laravel Anda)
    # # Cluster 0: Purple, Cluster 1: Blue, Cluster 2: Orange, Cluster 3: Red
    # color_map = {
    #     "0": "#dc2626", # Purple-600
    #     "1": "#ad1d1d", # Blue-600
    #     "2": "#921818", # Orange-600
    #     "3": "#5e0f0f"  # Red-600
    # }

    # # 2. Siapkan Data
    # df_plot = jumlah_pelanggan.reset_index()
    # df_plot.columns = ['Cluster', 'Total']
    # df_plot['Cluster'] = df_plot['Cluster'].astype(str) # Pastikan string untuk mapping warna

    # # 3. Buat Chart
    # fig = px.bar(
    #     df_plot, 
    #     x='Cluster', 
    #     y='Total',
    #     color='Cluster',
    #     color_discrete_map=color_map, # Menggunakan map warna di atas
    #     text='Total'
    # )

    # # 4. Styling Minimalis & "Kecil"
    # fig.update_layout(
    #     height=180, # Sangat ringkas untuk dashboard
    #     margin=dict(l=0, r=0, t=30, b=0),
    #     showlegend=False,
    #     plot_bgcolor="rgba(0,0,0,0)",
    #     paper_bgcolor="rgba(0,0,0,0)",
    #     xaxis_title=None,
    #     yaxis_title=None,
    #     font=dict(size=10) # Font kecil agar proporsional
    # )

    # # Sembunyikan garis dan angka sumbu Y agar bersih
    # fig.update_yaxes(visible=False)
    # fig.update_traces(
    #     textposition='outside', 
    #     cliponaxis=False,
    #     marker_line_width=0 # Menghilangkan border bar
    # )
    
       
    with open("deploy.py", encoding="utf-8") as f:
        kode_button = f.read()
        exec(kode_button)
    
else:
    st.error("Gagal terhubung ke API Laravel")

