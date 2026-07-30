# Perbandingan Schema Database E-Prospek

- Production dump: `sql_eprospek_bkk.sql`
- Database lokal: `pipeline_prospek`
- Tabel production: 23
- Tabel lokal: 23
- DDL production diproses: 23 CREATE TABLE, 45 ALTER TABLE

## Tabel ada di lokal tetapi belum ada di production
- `user_simpeg_syncs`

## Tabel hanya ada di production
- `prospects_kunjungan_ao`

## Detail field pada tabel yang berbeda
### `user_simpeg_syncs` — hanya ada di lokal
- `id` — `bigint unsigned`, nullable=NO, default=NULL, extra=auto_increment`
- `user_id` — `bigint unsigned`, nullable=YES, default=NULL, extra=-`
- `employee_id` — `varchar(64)`, nullable=NO, default=NULL, extra=-`
- `sync_status` — `varchar(16)`, nullable=NO, default=NULL, extra=-`
- `sync_message` — `varchar(500)`, nullable=YES, default=NULL, extra=-`
- `snapshot_data` — `json`, nullable=YES, default=NULL, extra=-`
- `synced_at` — `timestamp`, nullable=NO, default=NULL, extra=-`
- `created_at` — `timestamp`, nullable=YES, default=NULL, extra=-`
- `updated_at` — `timestamp`, nullable=YES, default=NULL, extra=-`

### `prospects_kunjungan_ao` — hanya ada di production
- `id` — `bigint unsigned`, nullable=NO, default=NULL, extra=auto_increment`
- `id_prospects` — `bigint unsigned`, nullable=YES, default=NULL, extra=-`
- `prospect_type` — `enum('kredit','tabungan','deposito','pembeli_aset','debitur_existing')`, nullable=NO, default=NULL, extra=-`
- `customer_name` — `varchar(200)`, nullable=NO, default=NULL, extra=-`
- `identity_number` — `varchar(20)`, nullable=YES, default=NULL, extra=-`
- `phone_number` — `varchar(20)`, nullable=NO, default=NULL, extra=-`
- `jenis_usaha` — `varchar(50)`, nullable=YES, default=NULL, extra=-`
- `rekomendasi_produk` — `enum('tabungan','deposito','kredit','aset')`, nullable=YES, default=NULL, extra=-`
- `keterangan_usaha` — `text`, nullable=YES, default=NULL, extra=-`
- `provinsi` — `varchar(100)`, nullable=YES, default=NULL, extra=-`
- `kab_kota` — `varchar(100)`, nullable=YES, default=NULL, extra=-`
- `kecamatan` — `varchar(100)`, nullable=YES, default=NULL, extra=-`
- `desa` — `varchar(100)`, nullable=YES, default=NULL, extra=-`
- `address` — `text`, nullable=YES, default=NULL, extra=-`
- `latitude` — `decimal(10,7)`, nullable=YES, default=NULL, extra=-`
- `longitude` — `decimal(10,7)`, nullable=YES, default=NULL, extra=-`
- `geo_address` — `text`, nullable=YES, default=NULL, extra=-`
- `foto_url` — `varchar(500)`, nullable=YES, default=NULL, extra=-`
- `kode_kantor` — `varchar(5)`, nullable=NO, default=NULL, extra=-`
- `description` — `text`, nullable=YES, default=NULL, extra=-`
- `created_by` — `varchar(20)`, nullable=NO, default=NULL, extra=-`
- `created_by_kode_kantor` — `varchar(5)`, nullable=NO, default=NULL, extra=-`
- `referral_by` — `varchar(20)`, nullable=YES, default=NULL, extra=-`
- `is_ao_input` — `tinyint(1)`, nullable=NO, default='0', extra=-`
- `delegation_status` — `enum('belum_didelegasikan','sudah_didelegasikan')`, nullable=NO, default='BELUM_DIDELEGASIKAN', extra=-`
- `assigned_to` — `varchar(20)`, nullable=YES, default=NULL, extra=-`
- `assigned_by` — `varchar(20)`, nullable=YES, default=NULL, extra=-`
- `assigned_at` — `datetime`, nullable=YES, default=NULL, extra=-`
- `status` — `enum('open','follow_up','sla','reject','closing')`, nullable=NO, default='OPEN', extra=-`
- `sla_started_at` — `datetime`, nullable=YES, default=NULL, extra=-`
- `sla_started_by` — `varchar(20)`, nullable=YES, default=NULL, extra=-`
- `rejected_at` — `datetime`, nullable=YES, default=NULL, extra=-`
- `reject_reason` — `varchar(255)`, nullable=YES, default=NULL, extra=-`
- `reject_note` — `text`, nullable=YES, default=NULL, extra=-`
- `closed_at` — `datetime`, nullable=YES, default=NULL, extra=-`
- `closing_account_number` — `varchar(30)`, nullable=YES, default=NULL, extra=-`
- `closing_realization_amount` — `bigint unsigned`, nullable=YES, default=NULL, extra=-`
- `closing_tenor` — `int unsigned`, nullable=YES, default=NULL, extra=-`
- `closing_note` — `text`, nullable=YES, default=NULL, extra=-`
- `closing_asset_name` — `varchar(200)`, nullable=YES, default=NULL, extra=-`
- `closing_buyer_name` — `varchar(200)`, nullable=YES, default=NULL, extra=-`
- `closing_asset_purchase_method` — `enum('lelang','cessie','lainnya')`, nullable=YES, default=NULL, extra=-`
- `created_at` — `datetime`, nullable=NO, default='CURRENT_TIMESTAMP', extra=-`
- `updated_at` — `datetime`, nullable=YES, default=NULL, extra=on update current_timestamp`

## Kolom ada di lokal tetapi belum ada di production
- `prospects.estimasi_nominal_realisasi` — `bigint unsigned`, nullable=YES, default=NULL, extra=-`

## Kolom hanya ada di production
- Tidak ada.

## Kolom dengan definisi berbeda
- Tidak ada.

## Indeks dan foreign key
- Indeks belum ada di production: 0
- Indeks bernama sama tetapi berbeda: 0
- Foreign key belum ada di production: 0
- Foreign key bernama sama tetapi berbeda: 0


## Catatan deployment
- Backup database production terlebih dahulu.
- Jalankan SQL upgrade satu kali pada maintenance window.
- Script tidak menghapus tabel/kolom production dan tidak otomatis mengubah definisi kolom yang sudah ada.
- Setelah deployment, jalankan kembali pembanding untuk memastikan selisih penambahan sudah nol.

## Hasil validasi SQL
- SQL upgrade berhasil diterapkan pada salinan schema production tanpa data.
- Setelah SQL diterapkan: 0 tabel, 0 kolom, 0 indeks, dan 0 foreign key lokal yang masih tertinggal.
