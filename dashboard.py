import streamlit as st
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import matplotlib.cm as cm
import seaborn as sns
import joblib
from sklearn.metrics import silhouette_samples, silhouette_score, accuracy_score, recall_score, f1_score, confusion_matrix
from sklearn.feature_selection import SelectKBest, f_classif 
# --- 1. CONFIG & ASSETS ---
st.set_page_config(page_title="Customer Intelligence Dashboard", layout="wide")
num_cols = ['tenure', 'MonthlyCharges', 'TotalCharges']

@st.cache_resource
def load_models():
    try:
        model = joblib.load('logistic_model.pkl')
        selector = joblib.load('selector.pkl')
        scaler = joblib.load('scaler.pkl')
        return model, selector, scaler
    except:
        return None, None, None

model, selector, scaler = load_models()


# --- 2. FUNGSI SILHOUETTE ---
def plot_manual_silhouette(X, labels):
    n_clusters = len(np.unique(labels))
    fig, ax1 = plt.subplots(figsize=(10, 6))
    silhouette_avg = silhouette_score(X, labels)
    sample_values = silhouette_samples(X, labels)
    y_lower = 10
    for i in range(n_clusters):
        ith_cluster_values = sample_values[labels == i]
        ith_cluster_values.sort()
        size_cluster_i = ith_cluster_values.shape[0]
        y_upper = y_lower + size_cluster_i
        color = cm.nipy_spectral(float(i) / n_clusters)
        ax1.fill_betweenx(np.arange(y_lower, y_upper), 0, ith_cluster_values,
                          facecolor=color, edgecolor=color, alpha=0.7)
        y_lower = y_upper + 10
    ax1.axvline(x=silhouette_avg, color="red", linestyle="--")
    ax1.set_title("Silhouette Plot Analysis")
    return fig, silhouette_avg

# --- 3. LOAD DATA ---
st.sidebar.header("Data Configuration")
excel_file = 'Tubes_Kelompok_PenambanganData.xlsx'

try:
    sheet_name = st.sidebar.selectbox("Pilih Sheet Data", 
                                     ["K-Means Cluster", "Logistic Regression", "Dataset Encode"])
    df = pd.read_excel(excel_file, sheet_name=sheet_name)
    st.sidebar.success(f"Sheet '{sheet_name}' dimuat!")
except:
    st.sidebar.warning("File Excel tidak ditemukan, mencoba membaca CSV...")
    df = pd.read_csv('Tubes_Kelompok_PenambanganData.xlsx - K-Means Cluster.csv')

dfRaw = pd.read_excel(excel_file, sheet_name="Dataset")
dfLogistic = pd.read_excel(excel_file, sheet_name="Logistic Regression")

def reduksi_data():
    # Pisahkan fitur dan target
    x = dfLogistic.drop(columns=['Churn', 'customerID', 'Contract'])
    y = dfLogistic['Churn']

    # Hapus kolom konstan
    x = x.loc[:, x.nunique() > 1]

    # Seleksi 3 fitur terbaik
    selector = SelectKBest(score_func=f_classif, k=3)
    X_new = selector.fit_transform(x, y)

    selected_features = x.columns[selector.get_support()].to_numpy()

    # Simpan selector untuk pipeline prediksi
    joblib.dump(selector, 'selector.pkl')

    return selected_features

reduksi_data()

# --- 4. UI DASHBOARD ---
st.title("📊 Customer Intelligence Dashboard")
tab1, tab2, tab3 = st.tabs(["💎 Clustering & Features", "📈 Churn Evaluation", "📋 Raw Data"])

with tab1:
    st.header("Analisis Klaster & Seleksi Fitur")
    
    # IF-ELSE 1: Cek keberadaan Selector dan Fitur Seleksi
    if selector is not None:
        st.subheader("1. Perbandingan Feature Selection")
        all_feats = selector.feature_names_in_
        selected_feats = selector.get_feature_names_out()
        
        col_f1, col_f2 = st.columns([1, 2])
        with col_f1:
            st.write(f"**Fitur Awal:** {len(all_feats)} kolom")
            st.write(f"**Fitur Terpilih:** {len(selected_feats)} kolom")
            st.success(f"Fitur numerik utama: {', '.join(num_cols)}")
        with col_f2:
            fig_bar, ax_bar = plt.subplots(figsize=(6, 2))
            sns.barplot(x=[len(all_feats), len(selected_feats)], y=['Awal', 'Terpilih'], palette='rocket', ax=ax_bar)
            st.pyplot(fig_bar)
    else:
        st.error("Model Selector (.pkl) tidak ditemukan.")

    st.divider()

    # IF-ELSE 2: Cek keberadaan kolom 'Cluster' untuk visualisasi Clustering
    if 'Cluster' in df.columns:
        col_a, col_b = st.columns(2)
        with col_a:
            st.subheader("2. Silhouette Plot")
            fig_sil, score = plot_manual_silhouette(df[num_cols], df['Cluster'])
            st.pyplot(fig_sil)
            st.metric("Avg Silhouette Score", f"{score:.3f}")
        
        with col_b:
            st.subheader("3. 3D Cluster Visualization")
            fig_3d = plt.figure(figsize=(10, 8))
            ax = fig_3d.add_subplot(111, projection='3d')
            scatter = ax.scatter(df['tenure'], df['MonthlyCharges'], df['TotalCharges'], 
                                c=df['Cluster'], cmap='viridis', s=40, alpha=0.6)
            ax.set_xlabel('Tenure')
            ax.set_ylabel('Monthly Charges')
            ax.set_zlabel('Total Charges')
            plt.colorbar(scatter, label='Cluster ID')
            st.pyplot(fig_3d)
        from sklearn.cluster import KMeans

# IF-ELSE: Cek apakah kolom numerik ada untuk WCSS/Elbow
        if all(col in df.columns for col in num_cols):
            st.subheader("2. Elbow Method (WCSS)")
            wcss = []
                # Hitung WCSS secara real-time untuk 1-10 klaster
            X_k = df[num_cols].dropna()
            for i in range(1, 11):
                kmeans = KMeans(n_clusters=i, init='k-means++', random_state=42)
                kmeans.fit(X_k) # Perbaikan: Menggunakan dataframe [[cols]]
                wcss.append(kmeans.inertia_)
                
            fig_elbow, ax_elbow = plt.subplots()
            ax_elbow.plot(range(1, 11), wcss, marker='o', linestyle='--')
            ax_elbow.set_title('Elbow Method')
            ax_elbow.set_xlabel('Number of Clusters')
            ax_elbow.set_ylabel('WCSS')
            st.pyplot(fig_elbow)
        else:
            st.warning("Kolom tenure/MonthlyCharges/TotalCharges tidak ditemukan untuk Elbow Method.")
    else:
        st.warning(f"Visualisasi Klaster tidak tersedia karena kolom 'Cluster' tidak ditemukan di sheet '{sheet_name}'.")

with tab2:
    X = dfLogistic[reduksi_data()]
    y = dfLogistic['Churn']

    from sklearn.model_selection import train_test_split

    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
)
    from sklearn.preprocessing import StandardScaler

    scaler_logistic = StandardScaler()
    X_train_scaled = scaler_logistic.fit_transform(X_train)
    X_test_scaled = scaler_logistic.transform(X_test)
    
    from sklearn.linear_model import LogisticRegression

    model_balanced = LogisticRegression(max_iter=1000, class_weight='balanced')
    model_balanced.fit(X_train_scaled, y_train)

    # Prediksi ulang
    y_pred = model_balanced.predict(X_test_scaled)
    st.header("Evaluasi Model Churn")
    col_tab1, col_tab2, col_tab3 = st.columns([1, 1,1])
    # 1. Mengambil nilai sebagai variabel
    accuracy = accuracy_score(y_test, y_pred)
    # Kita gunakan average='binary' karena ini adalah klasifikasi 2 kelas (Churn/Stay)
    recall = recall_score(y_test, y_pred) 
    f1 = f1_score(y_test, y_pred)

    with col_tab1:
        st.metric(label="Akurasi", value=f"{accuracy:.2%}")

    with col_tab2:
        st.metric(label="Recall", value=f"{recall:.2%}")

    with col_tab3: # Pastikan ini col_tab3, bukan col_tab2 lagi
        st.metric(label="F1-Score", value=f"{f1:.2%}")
    # IF-ELSE 3: Validasi kecocokan fitur sebelum Prediksi (Mencegah ValueError)
    if scaler is not None and model is not None:
        expected_features = scaler.feature_names_in_
        
        # Cek apakah semua kolom yang dibutuhkan ada di dataframe saat ini
        if set(expected_features).issubset(df.columns):
            col_m1, col_m2 = st.columns(2)
            
            with col_m1:
                st.subheader("Confusion Matrix")
                cm_val = confusion_matrix(y_test, y_pred)
                fig_cm, ax_cm = plt.subplots(figsize=(8, 6))
                sns.heatmap(cm_val, annot=True, fmt='d', cmap='Blues', 
                            xticklabels=['Stay', 'Churn'], yticklabels=['Stay', 'Churn'], ax=ax_cm)
                ax_cm.set_xlabel('Prediksi')
                ax_cm.set_ylabel('Aktual')
                st.pyplot(fig_cm)
                st.caption("Akurasi: 72.8%")

            with col_m2:
                st.subheader("Sebaran Probabilitas Churn")
                X_input = df[expected_features]
                X_scaled = scaler.transform(X_input)
                y_probs = model.predict_proba(X_scaled)[:, 1]
                y_actual = df['Churn'] if 'Churn' in df.columns else np.zeros(len(y_probs))

                fig_prob, ax_prob = plt.subplots(figsize=(10, 6))
                sc = ax_prob.scatter(range(len(y_probs)), y_probs, c=y_actual, cmap='coolwarm', alpha=0.6, s=20)
                ax_prob.axhline(y=0.5, color='red', linestyle='--', label='Threshold 0.5')
                ax_prob.set_ylabel('Probability')
                plt.colorbar(sc, label='Actual (Red=Churn, Blue=Stay)')
                st.pyplot(fig_prob)
        else:
            missing = set(expected_features) - set(df.columns)
            st.error(f"Fitur tidak lengkap untuk prediksi! Kolom yang hilang: {missing}")
            st.info("Saran: Gunakan sheet 'Dataset Encode' atau 'Logistic Regression'.")
    else:
        st.error("Model Churn atau Scaler (.pkl) tidak ditemukan.")
    
    # --- BAGIAN VISUALISASI FEATURE SCORES (ANOVA) ---
    st.subheader("Skor Fitur Berdasarkan ANOVA (SelectKBest)")
    
    if 'Churn' in df.columns:
        try:
            # Drop kolom non-fitur
            x_fs = df.drop(columns=['Churn', 'customerID', 'Contract', 'Cluster'], errors='ignore')
            y_fs = df['Churn']
            
            # Filter kolom yang konstan (nunique > 1)
            x_fs = x_fs.loc[:, x_fs.nunique() > 1]
            # Pastikan hanya kolom numerik untuk ANOVA
            x_fs_numeric = x_fs.select_dtypes(include=[np.number])

            if not x_fs_numeric.empty:
                # Jalankan SelectKBest
                selector_fs = SelectKBest(score_func=f_classif, k='all')
                selector_fs.fit(x_fs_numeric, y_fs)

                df_scores = pd.DataFrame({
                    'Feature': x_fs_numeric.columns,
                    'Score': selector_fs.scores_
                }).sort_values(by='Score', ascending=False)

                fig_fs, ax_fs = plt.subplots(figsize=(10, 6))
                sns.barplot(x='Score', y='Feature', data=df_scores, palette='viridis', ax=ax_fs)
                ax_fs.axhline(y=2.5, color='red', linestyle='--', label='Top Threshold')
                ax_fs.set_title('Feature Importance (F-Score)')
                st.pyplot(fig_fs)
                
            else:
                st.error("Tidak ada fitur numerik yang cukup untuk menghitung skor ANOVA.")
        except Exception as e:
            st.error(f"Gagal menghitung skor fitur: {e}")

    
with tab3:
    st.subheader("Data Explorer")
    # IF-ELSE 4: Cek apakah dataframe kosong
    if not df.empty:
        st.dataframe(df)
        st.download_button("Export to CSV", df.to_csv(index=False), "data_export.csv", "text/csv")
        
        st.subheader("Hasil Statistika Deskriptif")
        st.dataframe(dfRaw.describe())
        st.download_button("Export to CSV", dfRaw.describe().to_csv(index=False), "data_deskripstif.csv", "text/csv")
        
        st.subheader("Hasil Korelasi")
        st.dataframe(df.corr(numeric_only=True))
        dt_corr = pd.DataFrame(df.corr(numeric_only=True))
        st.download_button("Export to CSV", dt_corr.to_csv(index=True), "data_korelasi.csv", "text/csv")
        
        st.subheader("Hasil Keseluruhan")
        st.dataframe({
            'Tipe Data': df.dtypes,
            'Non-Null': df.count(),
            'Null': df.isnull().sum(),
            'Unique': df.nunique(),
        })

        st.download_button(
            label="Export Info Data ke CSV",
            data=pd.DataFrame({
            'Nama Kolom': df.columns,
            'Tipe Data': df.dtypes.values,
            'Non-Null': df.count().values,
            'Null': df.isnull().sum().values,
            'Unique': df.nunique().values
        }).to_csv(index=False),
            file_name="info_keseluruhan_data.csv",
            mime="text/csv"
        )

        
    else:
        st.write("Tidak ada data untuk ditampilkan.")