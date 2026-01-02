
(cd "ChurnPredicted-Web/" && php artisan serve --port=8000)  > /dev/null 2>&1 &

echo "Menjalankan Frontend Service di http://localhost:8000 ..."

(streamlit run app.py)  > /dev/null 2>&1 &

echo "Menjalankan Streamlit app.py"

# Selesai menjalankan semua layanan
echo "Semua layanan telah dijalankan."

echo "---------------------------------------------------"
echo " SELAMAT DATANG DI Churn Predicted Web SYSTEM "
echo "1. Tekan 1 untuk menghentikan semua layanan"
echo "2. Tekan 2 untuk Push perubahan ke repository git"
echo "---------------------------------------------------"

# Pilih untuk menghentikan layanan
input="Masukkan pilihan Anda: "
echo $input
read -r input
# gateway to read
if [ "$input" = 1 ]; then
    sh ./stop.sh
    elif [ "$input" = 2 ]; then
    sh ./git_exec.sh
else
    echo "Pilihan tidak valid. Keluar dari script."
    sh ./stop.sh
    exit 1
fi

echo "Menghentikan semua layanan..."