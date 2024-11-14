<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('userdata')) {
    function userdata($field)
    {
        $ci = &get_instance();
        return $ci->session->userdata($field);
    }
}

if (!function_exists('unset_userdata')) {
    function unset_userdata()
    {
        $ci = &get_instance();
        $data = array('daerah', 'cabang', 'nama', 'nik', 'glr_depan', 'glr_belakang', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'status_kawin', 'email', 'telp', 'profesi', 'profesi_lain', 'pekerjaan', 'tempat_kerja', 'step', 'prev', 'alamat', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'kodepos', 'pendidikan', 'jurusan', 'is_pesantren', 'bahasa', 'org', 'org_lain', 'tgl_daftar');
        return $ci->session->unset_userdata($data);
    }
}

if (!function_exists('protect_shelter')) {
    function protect_shelter()
    {
        $ci = &get_instance();
        return $ci->authentication->protect_shelter();
    }
}

if (!function_exists('IndonesianDate')) {
    function IndonesianDate($strDate, $day = false)
    {
        if (is_null($strDate)) return;
        $hari = array("null", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Ahad");
        $date = explode("-", nice_date($strDate, 'd-n-Y'));
        $bln = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        );
        $today = ($day) ? $hari[nice_date($strDate, 'N')] . ', ' : '';
        if ($date[0] != 'Invalid Date') {
            return $today . $date[0] . ' ' . $bln[$date[1] - 1] . ' ' . $date[2];
        } else {
            return '-';
        }
    }
}

if (!function_exists('set_selected')) {
    function set_selected($field, $value)
    {
        $ci = &get_instance();
        $usrdt = $ci->session->userdata($field);
        if (set_value($field) != '') {
            $selected = set_select($field, $value);
        } else {
            $selected = ($usrdt == $value) ? 'selected = "selected"' : '';
        }

        return $selected;
    }
}

if (!function_exists('radio_selected')) {
    function radio_selected($field, $value)
    {
        $ci = &get_instance();
        $usrdt = $ci->session->userdata($field);
        if (set_value($field) != '') {
            $selected = set_radio($field, $value);
        } else {
            $selected = ($usrdt == $value) ? 'checked' : '';
        }

        return $selected;
    }
}

function html_esc_url($enc_url)
{
    return html_escape(urldecode($enc_url));
}

function env_url($slug = '')
{
    $base_url = base_url();

    if (!is_mod_rewrite_enabled()) {
        $base_url .= 'index.php/';
    }

    $base_url .= $slug;

    return $base_url;
}

function is_mod_rewrite_enabled()
{
    $status = false;

    if (function_exists('apache_get_modules')) {
        $status = in_array('mod_rewrite', apache_get_modules());
    }

    return $status;
}

if (!function_exists('get_name_pesan')) {
    function get_name_pesan($id)
    {
        $ci = &get_instance();
        $ci->load->model('m_inbox');
        $user = $ci->m_inbox->get_realname_pesan($id);
        return $user->name;
    }
}

if (!function_exists('date_pesan')) {
    function date_pesan($date)
    {
        $ci = &get_instance();
        $bln = array(
            'Jan', 'Feb', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
            'Sept', 'Okt', 'Nov', 'Des'
        );
        $now = new datetime();
        $diff = date_diff($now, new datetime($date));
        /*if ($diff->format('%a') == 0) {
            $time = date('H:i', strtotime($date));
        } else {
            //$time = date('M', strtotime('2014-08-20'));
            $time = date('d', strtotime($date)).' '.$bln[date('n', strtotime($date))-1];
        }*/

        if ($diff->format('%y') > 0) {
            $time = date('d', strtotime($date)) . ' ' . $bln[date('n', strtotime($date)) - 1] . ' ' . date('Y', strtotime($date));
        } else if ($diff->format('%a') > 0) {
            $time = date('d', strtotime($date)) . ' ' . $bln[date('n', strtotime($date)) - 1];
        } else {
            $time = date('H:i', strtotime($date));
        }

        return $time;
    }
}

if (!function_exists('HitungUmur')) {
    function HitungUmur($strDate1 = NULL, $strDate2 = NULL)
    {
        $hrBln = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        if (is_null($strDate1)) return NULL;
        if (is_null($strDate2)) {
            $date2 = explode("-", date("d-n-Y"));
        } else {
            $date2 = explode("-", nice_date($strDate2, 'd-n-Y'));
        }
        $old_date = date($strDate1);              // returns Saturday, January 30 10 02:06:34
        $old_date_timestamp = strtotime($old_date);
        $new_date = date('d-n-Y', $old_date_timestamp);
        $date1 = explode("-", $new_date);

        if (isKabisat($date2[2])) {
            // jika tahun kabisat
            $hrBln[2] = 29;
        } else {
            // jika bukan tahun kabisat
            $hrBln[2] = 28;
        }

        $jmlTahun = $date2[2] - $date1[2];
        $jmlBulan = $date2[1] - $date1[1];
        $jmlHari = $date2[0] - $date1[0];

        // jika $jmlBulan negatif
        if ($jmlBulan < 0) {
            $jmlTahun--;
            $jmlBulan = 12 + $jmlBulan; // 12 + (-$jmlBulan)
        }

        // jika $jmlHari negatif
        if ($jmlHari < 0) {
            // hitung jumlah bulan
            if ($jmlBulan > 0) {
                $jmlBulan--;
            }
            if ($jmlBulan == 0) {
                $jmlBulan = 11;
                $jmlTahun--;
            }

            // hitung jumlah hari
            // sisa hari bulan sebelumnya = jumlah hari bulan sebelumnya - hari bulan sebelumnya
            $sisaHrBlnSebelumnya = $hrBln[$date2[1] - 1] - $date1[0];

            // jika sisanya negatif, maka tidak ada sisa hari
            if ($sisaHrBlnSebelumnya < 0) {
                $sisaHrBlnSebelumnya = 0;
            }

            // jumlah hari = sisa hari bulan sebelumnya + hari pada $date_2
            $jmlHari = $sisaHrBlnSebelumnya + $date2[0];
        }

        // mengembalikan selisih waktu dalam bentuk array(tahun, bulan, waktu)
        return array("tahun" => $jmlTahun, "bulan" => $jmlBulan, "hari" => $jmlHari);
    }
}

if (!function_exists('isKabisat')) {
    function isKabisat($thn)
    {
        // jika tahun habis dibagi 4, maka tahun kabisat
        if (($thn % 4) != 0) {
            return false;
        } // jika tidak habis dibagi 4, maka jika habis dibagi 100 dan 400 maka tahun kabisat
        else if ((($thn % 100) == 0) && (($thn % 400) != 0)) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('encode')) {
    function encode($str = NULL)
    {
        $ci = &get_instance();
        if ($ci->config->item('app_encrypt_mode') ==  TRUE) {
            $ci->load->library('encrypt');
            $ci->encrypt->set_mode(MCRYPT_MODE_CFB);
            //$ci->encrypt->set_cipher($ci->config->item('app_set_cipher'));
            $enc = str_replace('=', '', $ci->encrypt->encode($str));

            return strtr($enc, array('+' => '_', '/' => '-'));
        } else {
            return $str;
        }
    }
}

if (!function_exists('decode')) {
    function decode($str = NULL)
    {
        $ci = &get_instance();
        if ($ci->config->item('app_encrypt_mode') ==  TRUE) {
            $ci->load->library('encrypt');
            $ci->encrypt->set_mode(MCRYPT_MODE_CFB);
            //$ci->encrypt->set_cipher($ci->config->item('app_set_cipher'));
            $pad = strlen($str) % 4;
            if ($pad) {
                $padlen = 4 - $pad;
                $str .= str_repeat('=', $padlen);
            }
            return $ci->encrypt->decode(strtr($str, array('_' => '+', '-' => '/')));
        } else {
            return $str;
        }
    }
}

if (!function_exists('curl_api')) {
    function curl_api($data = array())
    {
        $content = NULL;

        if (function_exists('curl_version')) {
            //Server url
            $url = $data['url']; // http://172.10.27.4/rest/index.php?d=api&c=sdm&m=dosen
            $headers = array(
                'U4D-API-KEY: ' . $data['key']
            );

            try {
                $ch = curl_init();

                if (FALSE === $ch)
                    throw new Exception('failed to initialize');

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                if (isset($data['post'])) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data['post']);
                }

                $content = curl_exec($ch);

                if (FALSE === $content)
                    throw new Exception(curl_error($ch), curl_errno($ch));
                // ...process $content now
            } catch (Exception $e) {

                trigger_error(
                    sprintf(
                        'Curl failed with error #%d: %s',
                        $e->getCode(),
                        $e->getMessage()
                    ),
                    E_USER_ERROR
                );
            }
        }

        return $content;
    }
}

if (!function_exists('NoUsulan')) {
    function NoUsulan($last_number = null)
    {
        $counter    = is_null($last_number) ? '0' : intval(substr($last_number, 11, -7));
        $nomor      = strlen($counter + 1) > 3 ? $counter + 1 : str_pad(($counter + 1), 3, '0', STR_PAD_LEFT);
        return config_item('kode_keuangan') . '.' . config_item('kode_jenis_anggaran') . '.' . $nomor . '.' . date('dmy');
    }
}

if (!function_exists('UpdateNoUrut')) {
    function UpdateNoUrut($table_name, $field_name, $starting_number = 1)
    {
        $CI = &get_instance();
        $CI->load->database();

        $CI->db->select_max($field_name);
        $query = $CI->db->get($table_name);

        if ($query->num_rows() > 0) {
            $result = $query->row();
            $nomorUrut = intval($result->$field_name) + 1;
        } else {
            $nomorUrut = $starting_number;
        }

        $CI->db->update($table_name, [$field_name => $nomorUrut]);

        return $nomorUrut;
    }
}


if (!function_exists('GetListPeriode')) {
    function GetListPeriode($where = null)
    {
        $CI = &get_instance();
        $CI->load->database();



        if (!empty($where)) {
            $CI->db->where($where);
        }
        $CI->db->order_by('semId', 'DESC');

        $query = $CI->db->get('semester');

        return $query->result_array();
    }
}

if (!function_exists('GetConfigSys')) {
    function GetConfigSys($configCode = null)
    {
        $CI = &get_instance();
        $CI->load->database();



        if (!empty($configCode)) {
            $CI->db->where('ConfigCode', $configCode);
        }

        $query = $CI->db->get('sys_config');
        $config = $query->row_array();

        return $config['ConfigValue'];
    }
}

if (!function_exists('background_arsip')) {
    function background_arsip($id_enc, $eksemplar = NULL)
    {
        $ci = &get_instance();

        $ci->load->model('M_background');

        $unitId = get_user_unit_id();
        $suratId = decode(urldecode($id_enc));
        $detail_surat = $ci->M_background->detail_surat($suratId);

        if ($detail_surat['is_arsip'] != '') {
            // return TRUE;
            return TRUE;
            die;
        }

        #get berkasId
        $list_berkas = $ci->M_background->list_berkas($detail_surat['klasifikasiId'], $unitId);

        $berkas = '';
        #jika belum ada buat berkas baru
        if ($list_berkas == NULL) {
            $berkas = array(
                'brksUnitId' => $unitId,
                'brksNama' => 'Arsip Surat',
                'brksKlasifikasiId' => $detail_surat['jenis_id'],
                'brksNomor' => genereateBerkasNumber($detail_surat['klasifikasiId'])
            );
        }

        $params = array(
            'arsSuratId' => $suratId,
            'arsPerihal' => $detail_surat['perihal'],
            // 'arsBerkasId' => $berkasId,
            'arsJenisEksemplarId' => ($eksemplar == NULL) ? '1' : '2s',
            'arsJenis' => $detail_surat['jenis_id'],
            'arsFileArsip' => $detail_surat['file'],
            'arsTujuanUnitId' => $unitId,
            'arsUserCreate' => get_user_name(),
            'arsTglCreate' => date('Y-m-d H:i:s'),
        );

        if ($detail_surat['asal_surat'] == 'Eksternal') {
            $params['arsAsal'] = $detail_surat['id_asal_surat'];
        }
        if ($detail_surat['asal_surat'] == 'Internal') {
            $params['arsAsalUnitId'] = $detail_surat['id_asal_surat'];
        }

        $data = array(
            'arsip' => $params,
            'berkasId' => $list_berkas['id'],
            'berkas' => $berkas
        );

        $proses = $ci->M_background->set_arsip($data);
        return $proses['status'];
    }

    if (!function_exists('genereateBerkasNumber')) {
        function genereateBerkasNumber($id)
        {
            $ci = &get_instance();
            $ci->load->model('M_background');

            $detailKlasifikasi = $ci->M_background->getDetailKlaifikasi($id);
            $BerkasNumber = 'ARS/' . get_user_unit_kode() . '/' . $detailKlasifikasi['kode'] . '/' . $detailKlasifikasi['jns_kode'] . '/0001';
            return $BerkasNumber;
        }
    }
}
