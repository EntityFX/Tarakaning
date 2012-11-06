<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocaleService
 *
 * @author EntityFX
 */
final class LocaleService {

    private static $_locale = array(
        0 => 'aa',
        1 => 'aa_dj',
        2 => 'aa_er',
        3 => 'aa_et',
        4 => 'af',
        5 => 'af_na',
        6 => 'af_za',
        7 => 'agq',
        8 => 'agq_cm',
        9 => 'ak',
        10 => 'ak_gh',
        11 => 'am',
        12 => 'am_et',
        13 => 'ar',
        14 => 'ar_001',
        15 => 'ar_ae',
        16 => 'ar_bh',
        17 => 'ar_dz',
        18 => 'ar_eg',
        19 => 'ar_iq',
        20 => 'ar_jo',
        21 => 'ar_kw',
        22 => 'ar_lb',
        23 => 'ar_ly',
        24 => 'ar_ma',
        25 => 'ar_om',
        26 => 'ar_qa',
        27 => 'ar_sa',
        28 => 'ar_sd',
        29 => 'ar_sy',
        30 => 'ar_tn',
        31 => 'ar_ye',
        32 => 'as',
        33 => 'as_in',
        34 => 'asa',
        35 => 'asa_tz',
        36 => 'az',
        37 => 'az_arab',
        38 => 'az_arab_ir',
        39 => 'az_az',
        40 => 'az_cyrl',
        41 => 'az_cyrl_az',
        42 => 'az_ir',
        43 => 'az_latn',
        44 => 'az_latn_az',
        45 => 'bas',
        46 => 'bas_cm',
        47 => 'be',
        48 => 'be_by',
        49 => 'bem',
        50 => 'bem_zm',
        51 => 'bez',
        52 => 'bez_tz',
        53 => 'bg',
        54 => 'bg_bg',
        55 => 'bm',
        56 => 'bm_ml',
        57 => 'bn',
        58 => 'bn_bd',
        59 => 'bn_in',
        60 => 'bo',
        61 => 'bo_cn',
        62 => 'bo_in',
        63 => 'br',
        64 => 'br_fr',
        65 => 'brx',
        66 => 'brx_in',
        67 => 'bs',
        68 => 'bs_ba',
        69 => 'byn',
        70 => 'byn_er',
        71 => 'ca',
        72 => 'ca_es',
        73 => 'cch',
        74 => 'cch_ng',
        75 => 'cgg',
        76 => 'cgg_ug',
        77 => 'chr',
        78 => 'chr_us',
        79 => 'cs',
        80 => 'cs_cz',
        81 => 'cy',
        82 => 'cy_gb',
        83 => 'da',
        84 => 'da_dk',
        85 => 'dav',
        86 => 'dav_ke',
        87 => 'de',
        88 => 'de_at',
        89 => 'de_be',
        90 => 'de_ch',
        91 => 'de_de',
        92 => 'de_li',
        93 => 'de_lu',
        94 => 'dje',
        95 => 'dje_ne',
        96 => 'dua',
        97 => 'dua_cm',
        98 => 'dv',
        99 => 'dv_mv',
        100 => 'dyo',
        101 => 'dyo_sn',
        102 => 'dz',
        103 => 'dz_bt',
        104 => 'ebu',
        105 => 'ebu_ke',
        106 => 'ee',
        107 => 'ee_gh',
        108 => 'ee_tg',
        109 => 'el',
        110 => 'el_cy',
        111 => 'el_gr',
        112 => 'el_polyton',
        113 => 'en',
        114 => 'en_as',
        115 => 'en_au',
        116 => 'en_bb',
        117 => 'en_be',
        118 => 'en_bm',
        119 => 'en_bw',
        120 => 'en_bz',
        121 => 'en_ca',
        122 => 'en_dsrt',
        123 => 'en_dsrt_us',
        124 => 'en_gb',
        125 => 'en_gu',
        126 => 'en_gy',
        127 => 'en_hk',
        128 => 'en_ie',
        129 => 'en_in',
        130 => 'en_jm',
        131 => 'en_mh',
        132 => 'en_mp',
        133 => 'en_mt',
        134 => 'en_mu',
        135 => 'en_na',
        136 => 'en_nz',
        137 => 'en_ph',
        138 => 'en_pk',
        139 => 'en_sg',
        140 => 'en_shaw',
        141 => 'en_tt',
        142 => 'en_um',
        143 => 'en_us',
        144 => 'en_us_posix',
        145 => 'en_vi',
        146 => 'en_za',
        147 => 'en_zw',
        148 => 'en_zz',
        149 => 'eo',
        150 => 'es',
        151 => 'es_419',
        152 => 'es_ar',
        153 => 'es_bo',
        154 => 'es_cl',
        155 => 'es_co',
        156 => 'es_cr',
        157 => 'es_do',
        158 => 'es_ec',
        159 => 'es_es',
        160 => 'es_gq',
        161 => 'es_gt',
        162 => 'es_hn',
        163 => 'es_mx',
        164 => 'es_ni',
        165 => 'es_pa',
        166 => 'es_pe',
        167 => 'es_pr',
        168 => 'es_py',
        169 => 'es_sv',
        170 => 'es_us',
        171 => 'es_uy',
        172 => 'es_ve',
        173 => 'et',
        174 => 'et_ee',
        175 => 'eu',
        176 => 'eu_es',
        177 => 'ewo',
        178 => 'ewo_cm',
        179 => 'fa',
        180 => 'fa_af',
        181 => 'fa_ir',
        182 => 'ff',
        183 => 'ff_sn',
        184 => 'fi',
        185 => 'fi_fi',
        186 => 'fil',
        187 => 'fil_ph',
        188 => 'fo',
        189 => 'fo_fo',
        190 => 'fr',
        191 => 'fr_be',
        192 => 'fr_bf',
        193 => 'fr_bi',
        194 => 'fr_bj',
        195 => 'fr_bl',
        196 => 'fr_ca',
        197 => 'fr_cd',
        198 => 'fr_cf',
        199 => 'fr_cg',
        200 => 'fr_ch',
        201 => 'fr_ci',
        202 => 'fr_cm',
        203 => 'fr_dj',
        204 => 'fr_fr',
        205 => 'fr_ga',
        206 => 'fr_gf',
        207 => 'fr_gn',
        208 => 'fr_gp',
        209 => 'fr_gq',
        210 => 'fr_km',
        211 => 'fr_lu',
        212 => 'fr_mc',
        213 => 'fr_mf',
        214 => 'fr_mg',
        215 => 'fr_ml',
        216 => 'fr_mq',
        217 => 'fr_ne',
        218 => 'fr_re',
        219 => 'fr_rw',
        220 => 'fr_sn',
        221 => 'fr_td',
        222 => 'fr_tg',
        223 => 'fr_yt',
        224 => 'fur',
        225 => 'fur_it',
        226 => 'ga',
        227 => 'ga_ie',
        228 => 'gaa',
        229 => 'gaa_gh',
        230 => 'gd',
        231 => 'gd_gb',
        232 => 'gez',
        233 => 'gez_er',
        234 => 'gez_et',
        235 => 'gl',
        236 => 'gl_es',
        237 => 'gsw',
        238 => 'gsw_ch',
        239 => 'gu',
        240 => 'gu_in',
        241 => 'guz',
        242 => 'guz_ke',
        243 => 'gv',
        244 => 'gv_gb',
        245 => 'ha',
        246 => 'ha_arab',
        247 => 'ha_arab_ng',
        248 => 'ha_arab_sd',
        249 => 'ha_gh',
        250 => 'ha_latn',
        251 => 'ha_latn_gh',
        252 => 'ha_latn_ne',
        253 => 'ha_latn_ng',
        254 => 'ha_ne',
        255 => 'ha_ng',
        256 => 'ha_sd',
        257 => 'haw',
        258 => 'haw_us',
        259 => 'he',
        260 => 'he_il',
        261 => 'hi',
        262 => 'hi_in',
        263 => 'hr',
        264 => 'hr_hr',
        265 => 'hu',
        266 => 'hu_hu',
        267 => 'hy',
        268 => 'hy_am',
        269 => 'ia',
        270 => 'id',
        271 => 'id_id',
        272 => 'ig',
        273 => 'ig_ng',
        274 => 'ii',
        275 => 'ii_cn',
        276 => 'in',
        277 => 'is',
        278 => 'is_is',
        279 => 'it',
        280 => 'it_ch',
        281 => 'it_it',
        282 => 'iu',
        283 => 'iw',
        284 => 'ja',
        285 => 'ja_jp',
        286 => 'jmc',
        287 => 'jmc_tz',
        288 => 'ka',
        289 => 'ka_ge',
        290 => 'kab',
        291 => 'kab_dz',
        292 => 'kaj',
        293 => 'kaj_ng',
        294 => 'kam',
        295 => 'kam_ke',
        296 => 'kcg',
        297 => 'kcg_ng',
        298 => 'kde',
        299 => 'kde_tz',
        300 => 'kea',
        301 => 'kea_cv',
        302 => 'kfo',
        303 => 'kfo_ci',
        304 => 'khq',
        305 => 'khq_ml',
        306 => 'ki',
        307 => 'ki_ke',
        308 => 'kk',
        309 => 'kk_cyrl',
        310 => 'kk_cyrl_kz',
        311 => 'kk_kz',
        312 => 'kl',
        313 => 'kl_gl',
        314 => 'kln',
        315 => 'kln_ke',
        316 => 'km',
        317 => 'km_kh',
        318 => 'kn',
        319 => 'kn_in',
        320 => 'ko',
        321 => 'ko_kr',
        322 => 'kok',
        323 => 'kok_in',
        324 => 'kpe',
        325 => 'kpe_gn',
        326 => 'kpe_lr',
        327 => 'ksb',
        328 => 'ksb_tz',
        329 => 'ksf',
        330 => 'ksf_cm',
        331 => 'ksh',
        332 => 'ksh_de',
        333 => 'ku',
        334 => 'ku_arab',
        335 => 'ku_arab_iq',
        336 => 'ku_arab_ir',
        337 => 'ku_iq',
        338 => 'ku_ir',
        339 => 'ku_latn',
        340 => 'ku_latn_sy',
        341 => 'ku_latn_tr',
        342 => 'ku_sy',
        343 => 'ku_tr',
        344 => 'kw',
        345 => 'kw_gb',
        346 => 'ky',
        347 => 'ky_kg',
        348 => 'lag',
        349 => 'lag_tz',
        350 => 'lg',
        351 => 'lg_ug',
        352 => 'ln',
        353 => 'ln_cd',
        354 => 'ln_cg',
        355 => 'lo',
        356 => 'lo_la',
        357 => 'lt',
        358 => 'lt_lt',
        359 => 'lu',
        360 => 'lu_cd',
        361 => 'luo',
        362 => 'luo_ke',
        363 => 'luy',
        364 => 'luy_ke',
        365 => 'lv',
        366 => 'lv_lv',
        367 => 'mas',
        368 => 'mas_ke',
        369 => 'mas_tz',
        370 => 'mer',
        371 => 'mer_ke',
        372 => 'mfe',
        373 => 'mfe_mu',
        374 => 'mg',
        375 => 'mg_mg',
        376 => 'mgh',
        377 => 'mgh_mz',
        378 => 'mi',
        379 => 'mi_nz',
        380 => 'mk',
        381 => 'mk_mk',
        382 => 'ml',
        383 => 'ml_in',
        384 => 'mn',
        385 => 'mn_cn',
        386 => 'mn_cyrl',
        387 => 'mn_cyrl_mn',
        388 => 'mn_mn',
        389 => 'mn_mong',
        390 => 'mn_mong_cn',
        391 => 'mo',
        392 => 'mr',
        393 => 'mr_in',
        394 => 'ms',
        395 => 'ms_bn',
        396 => 'ms_my',
        397 => 'mt',
        398 => 'mt_mt',
        399 => 'mua',
        400 => 'mua_cm',
        401 => 'my',
        402 => 'my_mm',
        403 => 'naq',
        404 => 'naq_na',
        405 => 'nb',
        406 => 'nb_no',
        407 => 'nd',
        408 => 'nd_zw',
        409 => 'nds',
        410 => 'nds_de',
        411 => 'ne',
        412 => 'ne_in',
        413 => 'ne_np',
        414 => 'nl',
        415 => 'nl_aw',
        416 => 'nl_be',
        417 => 'nl_cw',
        418 => 'nl_nl',
        419 => 'nl_sx',
        420 => 'nmg',
        421 => 'nmg_cm',
        422 => 'nn',
        423 => 'nn_no',
        424 => 'no',
        425 => 'nr',
        426 => 'nr_za',
        427 => 'nso',
        428 => 'nso_za',
        429 => 'nus',
        430 => 'nus_sd',
        431 => 'ny',
        432 => 'ny_mw',
        433 => 'nyn',
        434 => 'nyn_ug',
        435 => 'oc',
        436 => 'oc_fr',
        437 => 'om',
        438 => 'om_et',
        439 => 'om_ke',
        440 => 'or',
        441 => 'or_in',
        442 => 'pa',
        443 => 'pa_arab',
        444 => 'pa_arab_pk',
        445 => 'pa_guru',
        446 => 'pa_guru_in',
        447 => 'pa_in',
        448 => 'pa_pk',
        449 => 'pl',
        450 => 'pl_pl',
        451 => 'ps',
        452 => 'ps_af',
        453 => 'pt',
        454 => 'pt_ao',
        455 => 'pt_br',
        456 => 'pt_gw',
        457 => 'pt_mz',
        458 => 'pt_pt',
        459 => 'pt_st',
        460 => 'rm',
        461 => 'rm_ch',
        462 => 'rn',
        463 => 'rn_bi',
        464 => 'ro',
        465 => 'ro_md',
        466 => 'ro_ro',
        467 => 'rof',
        468 => 'rof_tz',
        469 => 'root',
        470 => 'ru',
        471 => 'ru_md',
        472 => 'ru_ru',
        473 => 'ru_ua',
        474 => 'rw',
        475 => 'rw_rw',
        476 => 'rwk',
        477 => 'rwk_tz',
        478 => 'sa',
        479 => 'sa_in',
        480 => 'sah',
        481 => 'sah_ru',
        482 => 'saq',
        483 => 'saq_ke',
        484 => 'sbp',
        485 => 'sbp_tz',
        486 => 'se',
        487 => 'se_fi',
        488 => 'se_no',
        489 => 'seh',
        490 => 'seh_mz',
        491 => 'ses',
        492 => 'ses_ml',
        493 => 'sg',
        494 => 'sg_cf',
        495 => 'sh',
        496 => 'sh_ba',
        497 => 'sh_cs',
        498 => 'sh_yu',
        499 => 'shi',
        500 => 'shi_latn',
        501 => 'shi_latn_ma',
        502 => 'shi_ma',
        503 => 'shi_tfng',
        504 => 'shi_tfng_ma',
        505 => 'si',
        506 => 'si_lk',
        507 => 'sid',
        508 => 'sid_et',
        509 => 'sk',
        510 => 'sk_sk',
        511 => 'sl',
        512 => 'sl_si',
        513 => 'sn',
        514 => 'sn_zw',
        515 => 'so',
        516 => 'so_dj',
        517 => 'so_et',
        518 => 'so_ke',
        519 => 'so_so',
        520 => 'sq',
        521 => 'sq_al',
        522 => 'sr',
        523 => 'sr_ba',
        524 => 'sr_cs',
        525 => 'sr_cyrl',
        526 => 'sr_cyrl_ba',
        527 => 'sr_cyrl_cs',
        528 => 'sr_cyrl_me',
        529 => 'sr_cyrl_rs',
        530 => 'sr_cyrl_yu',
        531 => 'sr_latn',
        532 => 'sr_latn_ba',
        533 => 'sr_latn_cs',
        534 => 'sr_latn_me',
        535 => 'sr_latn_rs',
        536 => 'sr_latn_yu',
        537 => 'sr_me',
        538 => 'sr_rs',
        539 => 'sr_yu',
        540 => 'ss',
        541 => 'ss_sz',
        542 => 'ss_za',
        543 => 'ssy',
        544 => 'ssy_er',
        545 => 'st',
        546 => 'st_ls',
        547 => 'st_za',
        548 => 'sv',
        549 => 'sv_fi',
        550 => 'sv_se',
        551 => 'sw',
        552 => 'sw_ke',
        553 => 'sw_tz',
        554 => 'swc',
        555 => 'swc_cd',
        556 => 'syr',
        557 => 'syr_sy',
        558 => 'ta',
        559 => 'ta_in',
        560 => 'ta_lk',
        561 => 'te',
        562 => 'te_in',
        563 => 'teo',
        564 => 'teo_ke',
        565 => 'teo_ug',
        566 => 'tg',
        567 => 'tg_cyrl',
        568 => 'tg_cyrl_tj',
        569 => 'tg_tj',
        570 => 'th',
        571 => 'th_th',
        572 => 'ti',
        573 => 'ti_er',
        574 => 'ti_et',
        575 => 'tig',
        576 => 'tig_er',
        577 => 'tl',
        578 => 'tl_ph',
        579 => 'tn',
        580 => 'tn_za',
        581 => 'to',
        582 => 'to_to',
        583 => 'tr',
        584 => 'tr_tr',
        585 => 'trv',
        586 => 'trv_tw',
        587 => 'ts',
        588 => 'ts_za',
        589 => 'tt',
        590 => 'tt_ru',
        591 => 'twq',
        592 => 'twq_ne',
        593 => 'tzm',
        594 => 'tzm_latn',
        595 => 'tzm_latn_ma',
        596 => 'tzm_ma',
        597 => 'ug',
        598 => 'ug_arab',
        599 => 'ug_arab_cn',
        600 => 'ug_cn',
        601 => 'uk',
        602 => 'uk_ua',
        603 => 'ur',
        604 => 'ur_in',
        605 => 'ur_pk',
        606 => 'uz',
        607 => 'uz_af',
        608 => 'uz_arab',
        609 => 'uz_arab_af',
        610 => 'uz_cyrl',
        611 => 'uz_cyrl_uz',
        612 => 'uz_latn',
        613 => 'uz_latn_uz',
        614 => 'uz_uz',
        615 => 'vai',
        616 => 'vai_latn',
        617 => 'vai_latn_lr',
        618 => 'vai_vaii',
        619 => 'vai_vaii_lr',
        620 => 've',
        621 => 've_za',
        622 => 'vi',
        623 => 'vi_vn',
        624 => 'vun',
        625 => 'vun_tz',
        626 => 'wae',
        627 => 'wae_ch',
        628 => 'wal',
        629 => 'wal_et',
        630 => 'wo',
        631 => 'wo_latn',
        632 => 'wo_latn_sn',
        633 => 'wo_sn',
        634 => 'xh',
        635 => 'xh_za',
        636 => 'xog',
        637 => 'xog_ug',
        638 => 'yav',
        639 => 'yav_cm',
        640 => 'yo',
        641 => 'yo_ng',
        642 => 'zh',
        643 => 'zh_cn',
        644 => 'zh_hans',
        645 => 'zh_hans_cn',
        646 => 'zh_hans_hk',
        647 => 'zh_hans_mo',
        648 => 'zh_hans_sg',
        649 => 'zh_hant',
        650 => 'zh_hant_hk',
        651 => 'zh_hant_mo',
        652 => 'zh_hant_tw',
        653 => 'zh_hk',
        654 => 'zh_mo',
        655 => 'zh_sg',
        656 => 'zh_tw',
        657 => 'zu',
        658 => 'zu_za'
    );

    public static function getLocaleNameById($id) {
        if ($id >= 0 && $id <= 658) {
            return self::$_locale[$id];
        }
        return self::$_locale[470];
    }

    public static function getLocaleIdByName($localeName) {
        $res = array_search($localeName, self::$_locale, true);
        return !$res ? 470 : $res;
    }

}

?>