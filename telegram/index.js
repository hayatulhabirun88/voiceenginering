const TelegramBot = require("node-telegram-bot-api");
const axios = require("axios");
const sqlstring = require("sqlstring");

// replace the value below with the Telegram token you receive from @BotFather
const token = "6184062347:AAFrSvz-McjAc6yQ7tcDdnjWooJLIYHTGVg";

// Create a bot that uses 'polling' to fetch new updates
const bot = new TelegramBot(token, { polling: true });

const mysql = require("mysql");

const con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "voiceofenginering",
});

con.connect(function (err) {
    if (err) {
        bot.sendMessage(msg.chat.id, `Koneksi database gagal`);
    }else{
        console.log("Connected mysql!");
    }
});

// Mendengarkan perintah /start
bot.onText(/\/start/, (msg) => {
    const chatId = msg.chat.id;
    const username = msg.from.username;
    bot.sendMessage(chatId, `Selamat datang di The Voice Of Engineering Universitas Muhammadiah Sumatra Barat!. Silahkan Nilai Peserta Lomba dengan cara ketik: {nilai}.{kode_peserta} contoh: 100.XFT | untuk menilai anda dipersilahkan memasukan angka dari 60 s/d 100 dan mengirim vote dimulai ketika vote dibuka dan diakhiri ketika vote ditutup oleh panitia. Terima kasih selamat menonton`);
});

bot.on("message", (msg) => {
    if (msg.text != "/start") {
        const pmsg = msg.text.toString().toLowerCase().replace(/[^A-Za-z0-9.]/g, '').split('.');
        if (!isNaN(pmsg[0])) {
            if (pmsg[0] >= 60 && pmsg[0] <= 100) {
                if (pmsg[1] !== "") {
                    const cekstatusvote = `SELECT * FROM peserta WHERE kode = '${pmsg[1]}' `;
                    con.query(cekstatusvote, function (err, rcekstatusvote, fields) {
                        if (err) {
                            console.error('Error menjalankan query: ' + err.stack);
                            return;
                        }
                        if (rcekstatusvote != "") {
                            if (rcekstatusvote[0].vote_status == "Y") {
                                const dovote = `SELECT * FROM voter WHERE telegram_id = '${msg.chat.id}' AND peserta_id = '${rcekstatusvote[0].id}'`;
                                con.query(dovote, function(err, rdovote, fields){
                                    if (err) {
                                        console.error('Error menjalankan query: ' + err.stack);
                                        return;
                                    }
                                    if (rdovote != "") {
                                        bot.sendMessage(msg.chat.id, `Anda telah melakukan penilaian kepada peserta ${rcekstatusvote[0].nama_peserta}`);
                                    } else {
                                        const doinsertvote = `INSERT INTO voter (id, telegram_id, nama_voter, nilai, peserta_id, created_at, updated_at) VALUES (NULL, ${msg.chat.id}, '${msg.from.first_name} ${msg.from.last_name}', ${pmsg[0]}, ${rcekstatusvote[0].id}, NULL, NULL) `;
                                        con.query(doinsertvote, function(err, rdoinsertvote){
                                        if (err) {
                                            console.error('Error menjalankan query: ' + err.stack);
                                            return;
                                        } else {
                                            bot.sendMessage(msg.chat.id, `Anda berhasil melakukan penilaian an. ${rcekstatusvote[0].nama_peserta} dengan nilai ${pmsg[0]}`);
                                        }
                                        });
                                    }
                                });
                            }else {
                                bot.sendMessage(msg.chat.id, `Vote ditutup atau Kode peserta salah, mohon isi kembali dengan benar dengan mengetikan {nilai}.{kode_peserta}, contoh: 100.abc!`);
                            }
                        } else {
                            bot.sendMessage(msg.chat.id, `Vote ditutup atau Kode peserta salah, mohon isi kembali dengan benar dengan mengetikan {nilai}.{kode_peserta}, contoh: 100.abc! `);
                        }
            
                    }); 
                } else {
                    bot.sendMessage(msg.chat.id, `Kode peserta lomba yang anda masukan tidak boleh kosong contoh: 100.abc`);
                }
            } else {
                bot.sendMessage(msg.chat.id, `Nilai vote yang anda masukan harus angka 60 sampai 100`);
            }
    
        } else {
            bot.sendMessage(msg.chat.id, `Format nilai vote yang anda masukan salah, mohon isi kembali dengan benar dengan mengetikan {nilai}.{kode_peserta}, contoh: 100.abc`);
        }
    }


});
