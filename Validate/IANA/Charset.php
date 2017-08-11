<?php

/**
 * IANA Charset Validator Class | Validate\IANA\Charset.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\IANA;

use Next\Validate\Validator;    # Validator Interface

use Next\Components\Object;     # Object Class

/**
 * IANA Charset Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Charset extends Object implements Validator {

    /**
     * Character Set Regular Expression
     *
     * This list contains:
     *
     * <ul>
     *
     *     <li>
     *
     *         Alphanumeric Characters (for letters, upper and lowercased)
     *
     *     </li>
     *
     *     <li>
     *
     *         Some non-alphanumeric characters:
     *
     *         <ul>
     *
     *             <li>:  (colon)
     *             <li>_  (underscore)
     *             <li>+  (plus sign)
     *             <li>-  (minus sign or hyphen)
     *             <li>() (parentheses, open and close)
     *
     *         </ul>
     *
     *     </li>
     *
     * </ul>
     *
     * @var string
     */
    const CHARSET = '(?:[a-zA-Z0-9:_+)(-]+)';

    /**
     * Character Set List with Aliases
     *
     * @staticvar array $charsets
     *
     * @see http://www.iana.org/assignments/character-sets
     */
    private static $charsets = array(

        'ANSI_X3.4-1968' => array(

            'iso-ir-6', 'ANSI_X3.4-1986', 'ISO_646.irv:1991', 'ASCII',
            'ISO646-US', 'US-ASCII', 'us', 'IBM367', 'cp367', 'csASCII'
        ),

        'ISO_8859-1:1987' => array(

            'iso-ir-100', 'ISO_8859-1', 'ISO-8859-1', 'latin1', 'l1',
            'IBM819', 'CP819', 'csISOLatin1'
        ),

        'ISO_8859-2:1987' => array(

            'iso-ir-101', 'ISO_8859-2', 'ISO-8859-2', 'latin2', 'l2',
            'csISOLatin2'
        ),

        'ISO_8859-3:1988' => array(

            'iso-ir-109', 'ISO_8859-3', 'ISO-8859-3', 'latin3', 'l3',
            'csISOLatin3'
        ),

        'ISO_8859-4:1988' => array(

            'iso-ir-110', 'ISO_8859-4', 'ISO-8859-4', 'latin4', 'l4',
            'csISOLatin4'
        ),

        'ISO_8859-5:1988' => array(

            'iso-ir-144', 'ISO_8859-5', 'ISO-8859-5', 'cyrillic',
            'csISOLatinCyrillic'
        ),

        'ISO_8859-6:1987' => array(

            'iso-ir-127', 'ISO_8859-6', 'ISO-8859-6', 'ECMA-114',
            'ASMO-708', 'arabic', 'csISOLatinArabic'
        ),

        'ISO_8859-7:1987' => array(

            'iso-ir-126', 'ISO_8859-7', 'ISO-8859-7', 'ELOT_928', 'ECMA-118',
            'greek', 'greek8', 'csISOLatinGreek'
        ),

        'ISO_8859-8:1988' => array(

            'iso-ir-138', 'ISO_8859-8', 'ISO-8859-8', 'hebrew',
            'csISOLatinHebrew'
        ),

        'ISO_8859-9:1989' => array(

            'iso-ir-148', 'ISO_8859-9', 'ISO-8859-9', 'latin5', 'l5',
            'csISOLatin5'
        ),

        'ISO-8859-10' => array(

            'iso-ir-157', 'l6', 'ISO_8859-10:1992', 'csISOLatin6', 'latin6'
        ),

        'ISO_6937-2-add' => array(

            'iso-ir-142', 'csISOTextComm'
        ),

        'JIS_X0201' => array(

            'X0201', 'csHalfWidthKatakana'
        ),

        'JIS_Encoding' => array(

            'csJISEncoding'
        ),

        'Shift_JIS' => array(

            'MS_Kanji', 'csShiftJIS'
        ),

        'Extended_UNIX_Code_Packed_Format_for_Japanese' => array(

            'csEUCPkdFmtJapanese', 'EUC-JP'
        ),

        'Extended_UNIX_Code_Fixed_Width_for_Japanese' => array(

            'csEUCFixWidJapanese'
        ),

        'BS_4730' => array(

            'iso-ir-4', 'ISO646-GB', 'gb', 'uk', 'csISO4UnitedKingdom'
        ),

        'SEN_850200_C' => array(

            'iso-ir-11', 'ISO646-SE2', 'se2', 'csISO11SwedishForNames'
        ),

        'IT' => array(

            'iso-ir-15', 'ISO646-IT', 'csISO15Italian'
        ),

        'ES' => array(

            'iso-ir-17', 'ISO646-ES', 'csISO17Spanish'
        ),

        'DIN_66003' => array(

            'iso-ir-21', 'de', 'ISO646-DE', 'csISO21German'
        ),

        'S_4551-1' => array(

            'iso-ir-60', 'ISO646-NO', 'no', 'csISO60DanishNorwegian',
            'csISO60Norwegian1'
        ),

        'F_Z_62-010' => array(

            'iso-ir-69', 'ISO646-FR', 'fr', 'csISO69French'
        ),

        'ISO-10646-UTF-1' => array(

            'csISO10646UTF1'
        ),

        'ISO_646.basic:1983' => array(

            'ref', 'csISO646basic1983'
        ),

        'INVARIANT' => array(

            'csINVARIANT'
        ),

        'ISO_646.irv:1983' => array(

            'iso-ir-2', 'irv', 'csISO2IntlRefVersion'
        ),

        'ATS-SEFI' => array(

            'iso-ir-8-1', 'csNATSSEFI'
        ),

        'ATS-SEFI-ADD' => array(

            'iso-ir-8-2', 'csNATSSEFIADD'
        ),

        'ATS-DANO' => array(

            'iso-ir-9-1', 'csNATSDANO'
        ),

        'ATS-DANO-ADD' => array(

            'iso-ir-9-2', 'csNATSDANOADD'
        ),

        'SEN_850200_B' => array(

        'iso-ir-10', 'FI', 'ISO646-FI', 'ISO646-SE', 'se', 'csISO10Swedish'
        ),

        'KS_C_5601-1987' => array(

        'iso-ir-149', 'KS_C_5601-1989', 'KSC_5601', 'korean', 'csKSC56011987'
        ),

        'ISO-2022-KR' => array(

            'csISO2022KR'
        ),

        'EUC-KR' => array(

            'csEUCKR'
        ),

        'ISO-2022-JP' => array(

            'csISO2022JP'
        ),

        'ISO-2022-JP-2' => array(

            'csISO2022JP2'
        ),

        'JIS_C6220-1969-jp' => array(

        'JIS_C6220-1969', 'iso-ir-13', 'katakana', 'x0201-7',
                'csISO13JISC6220jp'
        ),

        'JIS_C6220-1969-ro' => array('

            iso-ir-14', 'jp', 'ISO646-JP',
                'csISO14JISC6220ro'
        ),

        'PT' => array(

            'iso-ir-16', 'ISO646-PT', 'csISO16Portuguese'
        ),

        'greek7-old' => array(

            'iso-ir-18', 'csISO18Greek7Old'
        ),

        'latin-greek' => array(

            'iso-ir-19', 'csISO19LatinGreek'
        ),

        'F_Z_62-010_(1973)' => array('

            iso-ir-25', 'ISO646-FR1',
                'csISO25French'
        ),

        'Latin-greek-1' => array(

            'iso-ir-27', 'csISO27LatinGreek1'
        ),

        'ISO_5427' => array(

            'iso-ir-37', 'csISO5427Cyrillic'
        ),

        'JIS_C6226-1978' => array(

            'iso-ir-42', 'csISO42JISC62261978'
        ),

        'BS_viewdata' => array(

            'iso-ir-47', 'csISO47BSViewdata'
        ),

        'INIS' => array(

            'iso-ir-49', 'csISO49INIS'
        ),

        'INIS-8' => array(

            'iso-ir-50', 'csISO50INIS8'
        ),

        'INIS-cyrillic' => array(

            'iso-ir-51', 'csISO51INISCyrillic'
        ),

        'ISO_5427:1981' => array(

            'iso-ir-54', 'ISO5427Cyrillic1981'
        ),

        'ISO_5428:1980' => array(

            'iso-ir-55', 'csISO5428Greek'
        ),

        'GB_1988-80' => array(

            'iso-ir-57', 'cn', 'ISO646-CN', 'csISO57GB1988'
        ),

        'GB_2312-80' => array(

            'iso-ir-58', 'chinese', 'csISO58GB231280'
        ),

        'S_4551-2' => array(

            'ISO646-NO2', 'iso-ir-61', 'no2', 'csISO61Norwegian2'
        ),

        'videotex-suppl' => array(

            'iso-ir-70', 'csISO70VideotexSupp1'
        ),

        'PT2' => array(

            'iso-ir-84', 'ISO646-PT2', 'csISO84Portuguese2'
        ),

        'ES2' => array(

            'iso-ir-85', 'ISO646-ES2', 'csISO85Spanish2'
        ),

        'MSZ_7795.3' => array(

            'iso-ir-86', 'ISO646-HU', 'hu', 'csISO86Hungarian'
        ),

        'JIS_C6226-1983' => array(

            'iso-ir-87', 'x0208', 'JIS_X0208-1983', 'csISO87JISX0208'
        ),

        'greek7' => array(

            'iso-ir-88', 'csISO88Greek7'
        ),

        'ASMO_449' => array(

            'ISO_9036', 'arabic7', 'iso-ir-89', 'csISO89ASMO449'
        ),

        'iso-ir-90' => array(

            'csISO90'
        ),

        'JIS_C6229-1984-a' => array(

            'iso-ir-91', 'jp-ocr-a', 'csISO91JISC62291984a'
        ),

        'JIS_C6229-1984-b' => array(

            'iso-ir-92', 'ISO646-JP-OCR-B', 'jp-ocr-b', 'csISO92JISC62991984b'
        ),

        'JIS_C6229-1984-b-add' => array(

            'iso-ir-93', 'jp-ocr-b-add', 'csISO93JIS62291984badd'
        ),

        'JIS_C6229-1984-hand' => array(

            'iso-ir-94', 'jp-ocr-hand', 'csISO94JIS62291984hand'
        ),

        'JIS_C6229-1984-hand-add' => array(

            'iso-ir-95', 'jp-ocr-hand-add', 'csISO95JIS62291984handadd'
        ),

        'JIS_C6229-1984-kana' => array(

            'iso-ir-96', 'csISO96JISC62291984kana'
        ),

        'ISO_2033-1983' => array(

            'iso-ir-98', 'e13b', 'csISO2033'
        ),

        'ANSI_X3.110-1983' => array(

            'iso-ir-99', 'CSA_T500-1983', 'NAPLPS', 'csISO99NAPLPS'
        ),

        'T.61-7bit' => array(

            'iso-ir-102', 'csISO102T617bit'
        ),

        'T.61-8bit' => array(

            'T.61', 'iso-ir-103', 'csISO103T618bit'
        ),

        'ECMA-cyrillic' => array(

            'iso-ir-111', 'KOI8-E', 'csISO111ECMACyrillic'
        ),

        'CSA_Z243.4-1985-1' => array(

            'iso-ir-121', 'ISO646-CA', 'csa7-1', 'ca', 'csISO121Canadian1'
        ),

        'CSA_Z243.4-1985-2' => array(

            'iso-ir-122', 'ISO646-CA2', 'csa7-2', 'csISO122Canadian2'
        ),

        'CSA_Z243.4-1985-gr' => array(

            'iso-ir-123', 'csISO123CSAZ24341985gr'
        ),

        'ISO_8859-6-E' => array(

            'csISO88596E', 'ISO-8859-6-E'
        ),

        'ISO_8859-6-I' => array(

            'csISO88596I', 'ISO-8859-6-I'
        ),

        'T.101-G2' => array(

            'iso-ir-128', 'csISO128T101G2'
        ),

        'ISO_8859-8-E' => array(

            'csISO88598E', 'ISO-8859-8-E'
        ),

        'ISO_8859-8-I' => array(

            'csISO88598I', 'ISO-8859-8-I'
        ),

        'CSN_369103' => array(

            'iso-ir-139', 'csISO139CSN369103'
        ),

        'JUS_I.B1.002' => array(

            'iso-ir-141', 'ISO646-YU', 'js', 'yu', 'csISO141JUSIB1002'
        ),

        'IEC_P27-1' => array(

            'iso-ir-143', 'csISO143IECP271'
        ),

        'JUS_I.B1.003-serb' => array(

            'iso-ir-146', 'serbian', 'csISO146Serbian'
        ),

        'JUS_I.B1.003-mac' => array(

            'macedonian', 'iso-ir-147', 'csISO147Macedonian'
        ),

        'greek-ccitt' => array(

            'iso-ir-150', 'csISO150', 'csISO150GreekCCITT'
        ),

        'C_NC00-10:81' => array(

            'cuba', 'iso-ir-151', 'ISO646-CU', 'csISO151Cuba'
        ),

        'ISO_6937-2-25' => array(

            'iso-ir-152', 'csISO6937Add'
        ),

        'GOST_19768-74' => array(

            'ST_SEV_358-88', 'iso-ir-153', 'csISO153GOST1976874'
        ),

        'ISO_8859-supp' => array(

            'iso-ir-154', 'latin1-2-5', 'csISO8859Supp'
        ),

        'ISO_10367-box' => array(

            'iso-ir-155', 'csISO10367Box'
        ),

        'latin-lap' => array(

            'lap', 'iso-ir-158', 'csISO158Lap'
        ),

        'JIS_X0212-1990' => array(

            'x0212', 'iso-ir-159', 'csISO159JISX02121990'
        ),

        'DS_2089' => array(

            'DS2089', 'ISO646-DK', 'dk', 'csISO646Danish'
        ),

        'us-dk' => array(

            'csUSDK'
        ),

        'dk-us' => array(

            'csDKUS'
        ),

        'KSC5636' => array(

            'ISO646-KR', 'csKSC5636'
        ),

        'UNICODE-1-1-UTF-7' => array(

            'csUnicode11UTF7'
        ),

        'ISO-2022-CN' => array(),

        'ISO-2022-CN-EXT' => array(),

        'UTF-8' => array(),

        'ISO-8859-13' => array(),

        'ISO-8859-14' => array(

            'iso-ir-199', 'ISO_8859-14:1998', 'ISO_8859-14', 'latin8',
            'iso-celtic', 'l8'
        ),

        'ISO-8859-15' => array(

            'ISO_8859-15', 'Latin-9'
        ),

        'ISO-8859-16' => array(

            'iso-ir-226', 'ISO_8859-16:2001', 'ISO_8859-16', 'latin10', 'l10'
        ),

        'GBK' => array(

            'CP936', 'MS936', 'windows-936'
        ),

        'GB18030' => array(),

        'OSD_EBCDIC_DF04_15' => array(),

        'OSD_EBCDIC_DF03_IRV' => array(),

        'OSD_EBCDIC_DF04_1' => array(),

        'ISO-11548-1' => array(

            'ISO_11548-1', 'ISO_TR_11548-1', 'csISO115481'
        ),

        'KZ-1048' => array(

            'STRK1048-2002', 'RK1048', 'csKZ1048'
        ),

        'ISO-10646-UCS-2' => array(

            'csUnicode'
        ),

        'ISO-10646-UCS-4' => array(

            'csUCS4'
        ),

        'ISO-10646-UCS-Basic' => array(

            'csUnicodeASCII'
        ),

        'ISO-10646-Unicode-Latin1' => array(

            'csUnicodeLatin1', 'ISO-10646'
        ),

        'ISO-10646-J-1' => array(),

        'ISO-Unicode-IBM-1261' => array(

            'csUnicodeIBM1261'
        ),

        'ISO-Unicode-IBM-1268' => array(

            'csUnicodeIBM1268'
        ),

        'ISO-Unicode-IBM-1276' => array(

            'csUnicodeIBM1276'
        ),

        'ISO-Unicode-IBM-1264' => array(

            'csUnicodeIBM1264'
        ),

        'ISO-Unicode-IBM-1265' => array(

            'csUnicodeIBM1265'
        ),

        'UNICODE-1-1' => array(

            'csUnicode11'
        ),

        'SCSU' => array(),

        'UTF-7' => array(),

        'UTF-16BE' => array(),

        'UTF-16LE' => array(),

        'UTF-16' => array(),

        'CESU-8' => array(

            'csCESU-8'
        ),

        'UTF-32' => array(),

        'UTF-32BE' => array(),

        'UTF-32LE' => array(),

        'BOCU-1' => array(

            'csBOCU-1'
        ),

        'ISO-8859-1-Windows-3.0-Latin-1' => array(

            'csWindows30Latin1'
        ),

        'ISO-8859-1-Windows-3.1-Latin-1' => array(

            'csWindows31Latin1'
        ),

        'ISO-8859-2-Windows-Latin-2' => array(

            'csWindows31Latin2'
        ),

        'ISO-8859-9-Windows-Latin-5' => array(

            'csWindows31Latin5'
        ),

        'hp-roman8' => array(

            'roman8', 'r8', 'csHPRoman8'
        ),

        'Adobe-Standard-Encoding' => array(

            'csAdobeStandardEncoding'
        ),

        'Ventura-US' => array(

            'csVenturaUS'
        ),

        'Ventura-International' => array(

            'csVenturaInternational'
        ),

        'DEC-MCS' => array(

            'dec', 'csDECMCS'
        ),

        'IBM850' => array(

            'cp850', '850', 'csPC850Multilingual'
        ),

        'PC8-Danish-Norwegian' => array(

            'csPC8DanishNorwegian'
        ),

        'IBM862' => array(

            'cp862', '862', 'csPC862LatinHebrew'
        ),

        'PC8-Turkish' => array(

            'csPC8Turkish'
        ),

        'IBM-Symbols' => array(

            'csIBMSymbols'
        ),

        'IBM-Thai' => array(

            'csIBMThai'
        ),

        'HP-Legal' => array(

            'csHPLegal'
        ),

        'HP-Pi-font' => array(

            'csHPPiFont'
        ),

        'HP-Math8' => array(

            'csHPMath8'
        ),

        'Adobe-Symbol-Encoding' => array(

            'csHPPSMath'
        ),

        'HP-DeskTop' => array(

            'csHPDesktop'
        ),

        'Ventura-Math' => array(

            'csVenturaMath'
        ),

        'Microsoft-Publishing' => array(

            'csMicrosoftPublishing'
        ),

        'Windows-31J' => array(

            'csWindows31J'
        ),

        'GB2312' => array(

            'csGB2312'
        ),

        'Big5' => array(

            'csBig5'
        ),

        'cintosh' => array(

            'mac', 'csMacintosh'
        ),

        'IBM037' => array(

            'cp037', 'ebcdic-cp-us', 'ebcdic-cp-ca', 'ebcdic-cp-wt',
            'ebcdic-cp-nl', 'csIBM037'
        ),

        'IBM038' => array(

            'EBCDIC-INT', 'cp038', 'csIBM038'
        ),

        'IBM273' => array(

            'CP273', 'csIBM273'
        ),

        'IBM274' => array(

            'EBCDIC-BE', 'CP274', 'csIBM274'
        ),

        'IBM275' => array(

            'EBCDIC-BR', 'cp275', 'csIBM275'
        ),

        'IBM277' => array(

            'EBCDIC-CP-DK', 'EBCDIC-CP-NO', 'csIBM277'
        ),

        'IBM278' => array(

            'CP278', 'ebcdic-cp-fi', 'ebcdic-cp-se', 'csIBM278'
        ),

        'IBM280' => array(

            'CP280', 'ebcdic-cp-it', 'csIBM280'
        ),

        'IBM281' => array(

            'EBCDIC-JP-E', 'cp281', 'csIBM281'
        ),

        'IBM284' => array(

            'CP284', 'ebcdic-cp-es', 'csIBM284'
        ),

        'IBM285' => array(

            'CP285', 'ebcdic-cp-gb', 'csIBM285'
        ),

        'IBM290' => array(

            'cp290', 'EBCDIC-JP-kana', 'csIBM290'
        ),

        'IBM297' => array(

            'cp297', 'ebcdic-cp-fr', 'csIBM297'
        ),

        'IBM420' => array(

            'cp420', 'ebcdic-cp-ar1', 'csIBM420'
        ),

        'IBM423' => array(

            'cp423', 'ebcdic-cp-gr', 'csIBM423'
        ),

        'IBM424' => array(

            'cp424', 'ebcdic-cp-he', 'csIBM424'
        ),

        'IBM437' => array(

            'cp437', '437', 'csPC8CodePage437'
        ),

        'IBM500' => array(

            'CP500', 'ebcdic-cp-be', 'ebcdic-cp-ch', 'csIBM500'
        ),

        'IBM851' => array(

            'cp851', '851', 'csIBM851'
        ),

        'IBM852' => array(

            'cp852', '852', 'csPCp852'
        ),

        'IBM855' => array(

            'cp855', '855', 'csIBM855'
        ),

        'IBM857' => array(

            'cp857', '857', 'csIBM857'
        ),

        'IBM860' => array(

            'cp860', '860', 'csIBM860'
        ),

        'IBM861' => array(

            'cp861', '861', 'cp-is', 'csIBM861'
        ),

        'IBM863' => array(

            'cp863', '863', 'csIBM863'
        ),

        'IBM864' => array(

            'cp864', 'csIBM864'
        ),

        'IBM865' => array(

            'cp865', '865', 'csIBM865'
        ),

        'IBM868' => array(

            'CP868', 'cp-ar', 'csIBM868'
        ),

        'IBM869' => array(

            'cp869', '869', 'cp-gr', 'csIBM869'
        ),

        'IBM870' => array(

            'CP870', 'ebcdic-cp-roece', 'ebcdic-cp-yu', 'csIBM870'
        ),

        'IBM871' => array(

            'CP871', 'ebcdic-cp-is', 'csIBM871'
        ),

        'IBM880' => array(

            'cp880', 'EBCDIC-Cyrillic', 'csIBM880'
        ),

        'IBM891' => array(

            'cp891', 'csIBM891'
        ),

        'IBM903' => array(

            'cp903', 'csIBM903'
        ),

        'IBM904' => array(

            'cp904', '904', 'csIBBM904'
        ),

        'IBM905' => array(

            'CP905', 'ebcdic-cp-tr', 'csIBM905'
        ),

        'IBM918' => array(

            'CP918', 'ebcdic-cp-ar2', 'csIBM918'
        ),

        'IBM1026' => array(

            'CP1026', 'csIBM1026'
        ),

        'EBCDIC-AT-DE' => array(

            'csIBMEBCDICATDE'
        ),

        'EBCDIC-AT-DE-A' => array(

            'csEBCDICATDEA'
        ),

        'EBCDIC-CA-FR' => array(

            'csEBCDICCAFR'
        ),

        'EBCDIC-DK-NO' => array(

            'csEBCDICDKNO'
        ),

        'EBCDIC-DK-NO-A' => array(

            'csEBCDICDKNOA'
        ),

        'EBCDIC-FI-SE' => array(

            'csEBCDICFISE'
        ),

        'EBCDIC-FI-SE-A' => array(

            'csEBCDICFISEA'
        ),

        'EBCDIC-FR' => array(

            'csEBCDICFR'
        ),

        'EBCDIC-IT' => array(

            'csEBCDICIT'
        ),

        'EBCDIC-PT' => array(

            'csEBCDICPT'
        ),

        'EBCDIC-ES' => array(

            'csEBCDICES'
        ),

        'EBCDIC-ES-A' => array(

            'csEBCDICESA'
        ),

        'EBCDIC-ES-S' => array(

            'csEBCDICESS'
        ),

        'EBCDIC-UK' => array(

            'csEBCDICUK'
        ),

        'EBCDIC-US' => array(

            'csEBCDICUS'
        ),

        'UNKNOWN-8BIT' => array(

            'csUnknown8BiT'
        ),

        'MNEMONIC' => array(

            'csMnemonic'
        ),

        'MNEM' => array(

            'csMnem'
        ),

        'VISCII' => array(

            'csVISCII'
        ),

        'VIQR' => array(

            'csVIQR'
        ),

        'KOI8-R' => array(

            'csKOI8R'
        ),

        'HZ-GB-2312' => array(),

        'IBM866' => array(

            'cp866', '866', 'csIBM866'
        ),

        'IBM775' => array(

            'cp775', 'csPC775Baltic'
        ),

        'KOI8-U' => array(),

        'IBM00858' => array(

            'CCSID00858', 'CP00858', 'PC-Multilingual-850+euro'
        ),

        'IBM00924' => array(

            'CCSID00924', 'CP00924', 'ebcdic-Latin9--euro'
        ),

        'IBM01140' => array(

            'CCSID01140', 'CP01140', 'ebcdic-us-37+euro'
        ),

        'IBM01141' => array(

            'CCSID01141', 'CP01141', 'ebcdic-de-273+euro'
        ),

        'IBM01142' => array(

            'CCSID01142', 'CP01142', 'ebcdic-dk-277+euro', 'ebcdic-no-277+euro'
        ),

        'IBM01143' => array(

            'CCSID01143', 'CP01143', 'ebcdic-fi-278+euro', 'ebcdic-se-278+euro'
        ),

        'IBM01144' => array(

            'CCSID01144', 'CP01144', 'ebcdic-it-280+euro'
        ),

        'IBM01145' => array(

            'CCSID01145', 'CP01145', 'ebcdic-es-284+euro'
        ),

        'IBM01146' => array(

            'CCSID01146', 'CP01146', 'ebcdic-gb-285+euro'
        ),

        'IBM01147' => array(

            'CCSID01147', 'CP01147', 'ebcdic-fr-297+euro'
        ),

        'IBM01148' => array(

        'CCSID01148', 'CP01148', 'ebcdic-international-500+euro'
        ),

        'IBM01149' => array(

            'CCSID01149', 'CP01149', 'ebcdic-is-871+euro'
        ),

        'Big5-HKSCS' => array(),

        'IBM1047' => array(

            'IBM-1047'
        ),

        'PTCP154' => array(

            'csPTCP154', 'PT154', 'CP154', 'Cyrillic-Asian'
        ),

        'Amiga-1251' => array(

            'Ami1251', 'Amiga1251', 'Ami-1251'
        ),

        'KOI7-switched' => array(),

        'BRF' => array(

            'csBRF'
        ),

        'TSCII' => array(

            'csTSCII'
        ),

        'CP51932' => array(

            'csCP51932'
        ),

        'windows-874' => array(),

        'windows-1250' => array(),

        'windows-1251' => array(),

        'windows-1252' => array(),

        'windows-1253' => array(),

        'windows-1254' => array(),

        'windows-1255' => array(),

        'windows-1256' => array(),

        'windows-1257' => array(),

        'windows-1258' => array(),

        'TIS-620' => array()
    );

    // Validator Interface Interface Methods

    /**
     * Validates given Character Set
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        // We have to check in Registry keys (common names) and its sub-arrays (aliases)

        $source = array_merge( array_keys( self::$charsets ), self::$charsets );

        return in_array( $this -> options -> value, $source );
    }

    // Accessors

    /**
     * Get Charset Registry
     *
     * @return array
     *  Charsets List
     */
    public static function getCharsets() {
        return self::$charsets;
    }
}
