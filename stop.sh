
#!/bin/bash

#mematikan semua layanan ketika script dihentikan
trap "pkill -f 'php artisan serve';  pkill -f 'php spark serve' ; pkill -f 'streamlit'" EXIT

echo "--- Semua layanan telah dihentikan ---"
exit 0