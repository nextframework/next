<?php

/**
 * IANA MIME-Type Validator Class | Validation\IANA\MIME.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\IANA;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * The IANA MIME-Type Validator checks if given MIME-Type is valid towards the
 * IANA's list of Media Types
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 */
class MIME extends Object implements Validator {

    /**
     * Range Types
     *
     * @var string
     */
    const RANGE = '(?:\*/\*| # */*

                    # MIME Types "Categories"/* (i.e. text/*)

                    (?:application|audio|font|image|message|model|multipart|text|video)/\*|

                    # MIME Types "Categories"/[Alpha, hyphen and plus] (i.e text/plain)

                    (?:application|audio|font|image|message|model|multipart|text|video)/[A-Za-z0-9._+-]+
                   )';

    /**
     * MIME Types
     *
     * @var string
     */
    const MIME = '(?:application|audio|font|example|image|message|model|multipart|text|video)/[A-Za-z0-9._+-]+';

    /**
     * MIME Types
     *
     * @var array
     *
     * @see http://www.iana.org/assignments/media-types/index.html
     */
    CONST MIMES_LIST = [

        'application'  => [

            'x-www-form-urlencoded',  // It's not valid, but it's widely accepted

            '1d-interleaved-parityfec', '3gpp-ims+xml', 'activemessage',
            'andrew-inset', 'applefile', 'atom+xml', 'atomicmail',
            'atomcat+xml', 'atomsvc+xml', 'auth-policy+xml',
            'batch-SMTP', 'beep+xml', 'calendar+xml', 'cals-1840',
            'ccmp+xml', 'ccxml+xml', 'cdmi-capability', 'cdmi-container',
            'cdmi-domain', 'cdmi-object', 'cdmi-queue', 'cea-2018+xml',
            'cellml+xml', 'cfw', 'cnrp+xml', 'commonground',
            'conference-info+xml', 'cpl+xml', 'csta+xml', 'CSTAdata+xml',
            'cybercash', 'davmount+xml', 'dca-rft', 'dec-dx',
            'dialog-info+xml', 'dicom', 'dns', 'dskpp+xml', 'dssc+der',
            'dssc+xml', 'dvcs', 'ecmascript', 'EDI-Consent', 'EDIFACT',
            'EDI-X12', 'emma+xml', 'epp+xml', 'eshop', 'example', 'exi',
            'fastinfoset', 'fastsoap', 'fits', 'font-tdpfr',
            'framework-attributes+xml', 'H224', 'held+xml', 'http',
            'hyperstudio', 'ibe-key-request+xml', 'ibe-pkg-reply+xml',
            'ibe-pp-data', 'iges', 'im-iscomposing+xml', 'index',
            'index.cmd', 'index.obj', 'index.response', 'index.vnd',
            'inkml+xml', 'iotp', 'ipfix', 'ipp', 'isup', 'javascript',
            'json', 'kpml-request+xml', 'kpml-response+xml', 'lost+xml',
            'mac-binhex40', 'macwriteii', 'mads+xml', 'marc',
            'marcxml+xml', 'mathematica', 'mathml-content+xml',
            'mathml-presentation+xml', 'mathml+xml',
            'mbms-associated-procedure-description+xml',
            'mbms-deregister+xml', 'mbms-envelope+xml',
            'mbms-msk-response+xml', 'mbms-msk+xml',
            'mbms-protection-description+xml',
            'mbms-reception-report+xml', 'mbms-register-response+xml',
            'mbms-register+xml', 'mbms-user-service-description+xml',
            'mbox', 'media_control+xml', 'mediaservercontrol+xml',
            'metalink4+xml', 'mets+xml', 'mikey', 'mods+xml',
            'moss-keys', 'moss-signature', 'mosskey-data',
            'mosskey-request', 'mp21', 'mp4', 'mpeg4-generic',
            'mpeg4-iod', 'mpeg4-iod-xmt', 'msc-ivr+xml', 'msc-mixer+xml',
            'msword', 'mxf', 'nasdata', 'news-checkgroups',
            'news-groupinfo', 'news-transmission', 'nss', 'ocsp-request',
            'ocsp-response', 'octet-stream', 'oda', 'oebps-package+xml',
            'ogg', 'oxps', 'parityfec', 'patch-ops-error+xml', 'pdf',
            'pgp-encrypted', 'pgp-keys', 'pgp-signature', 'pidf+xml',
            'pidf-diff+xml', 'pkcs10', 'pkcs7-mime', 'pkcs7-signature',
            'pkcs8', 'pkix-attr-cert', 'pkix-cert', 'pkixcmp',
            'pkix-crl', 'pkix-pkipath', 'pls+xml', 'poc-settings+xml',
            'postscript', 'prs.alvestrand.titrax-sheet', 'prs.cww',
            'prs.nprend', 'prs.plucker', 'prs.rdf-xml-crypt',
            'prs.xsf+xml', 'pskc+xml', 'rdf+xml', 'qsig', 'reginfo+xml',
            'relax-ng-compact-syntax', 'remote-printing',
            'resource-lists-diff+xml', 'resource-lists+xml', 'riscos',
            'rlmi+xml', 'rls-services+xml', 'rpki-manifest', 'rpki-roa',
            'rpki-updown', 'rtf', 'rtx', 'samlassertion+xml',
            'samlmetadata+xml', 'sbml+xml', 'scvp-cv-request',
            'scvp-cv-response', 'scvp-vp-request', 'scvp-vp-response',
            'sdp', 'set-payment', 'set-payment-initiation',
            'set-registration', 'set-registration-initiation', 'sgml',
            'sgml-open-catalog', 'shf+xml', 'sieve', 'simple-filter+xml',
            'simple-message-summary', 'simpleSymbolContainer', 'slate',
            'smil (OBSOLETE)', 'smil+xml', 'soap+fastinfoset',
            'soap+xml', 'sparql-query', 'sparql-results+xml',
            'spirits-event+xml', 'srgs', 'srgs+xml', 'sru+xml',
            'ssml+xml', 'tamp-apex-update', 'tamp-apex-update-confirm',
            'tamp-community-update', 'tamp-community-update-confirm',
            'tamp-error', 'tamp-sequence-adjust',
            'tamp-sequence-adjust-confirm', 'tamp-status-query',
            'tamp-status-response', 'tamp-update', 'tamp-update-confirm',
            'tei+xml', 'thraud+xml', 'timestamp-query',
            'timestamp-reply', 'timestamped-data', 'tve-trigger',
            'ulpfec', 'vcard+xml', 'vemmi', 'vnd.3gpp.bsf+xml',
            'vnd.3gpp.pic-bw-large', 'vnd.3gpp.pic-bw-small',
            'vnd.3gpp.pic-bw-var', 'vnd.3gpp.sms',
            'vnd.3gpp2.bcmcsinfo+xml', 'vnd.3gpp2.sms', 'vnd.3gpp2.tcap',
            'vnd.3M.Post-it-Notes', 'vnd.accpac.simply.aso',
            'vnd.accpac.simply.imp', 'vnd.acucobol', 'vnd.acucorp',
            'vnd.adobe.fxp', 'vnd.adobe.partial-upload',
            'vnd.adobe.xdp+xml', 'vnd.adobe.xfdf', 'vnd.aether.imp',
            'vnd.ah-barcode', 'vnd.ahead.space',
            'vnd.airzip.filesecure.azf', 'vnd.airzip.filesecure.azs',
            'vnd.americandynamics.acc', 'vnd.amiga.ami',
            'vnd.amundsen.maze+xml',
            'vnd.anser-web-certificate-issue-initiation',
            'vnd.antix.game-component', 'vnd.apple.mpegurl',
            'vnd.apple.installer+xml', 'vnd.arastra.swi (OBSOLETE)',
            'vnd.aristanetworks.swi', 'vnd.astraea-software.iota',
            'vnd.audiograph', 'vnd.autopackage', 'vnd.avistar+xml',
            'vnd.blueice.multipass', 'vnd.bluetooth.ep.oob', 'vnd.bmi',
            'vnd.businessobjects', 'vnd.cab-jscript', 'vnd.canon-cpdl',
            'vnd.canon-lips', 'vnd.cendio.thinlinc.clientconf',
            'vnd.chemdraw+xml', 'vnd.chipnuts.karaoke-mmd',
            'vnd.cinderella', 'vnd.cirpack.isdn-ext', 'vnd.claymore',
            'vnd.cloanto.rp9', 'vnd.clonk.c4group',
            'vnd.cluetrust.cartomobile-config',
            'vnd.cluetrust.cartomobile-config-pkg',
            'vnd.collection+json', 'vnd.commerce-battelle',
            'vnd.commonspace', 'vnd.cosmocaller', 'vnd.contact.cmsg',
            'vnd.crick.clicker', 'vnd.crick.clicker.keyboard',
            'vnd.crick.clicker.palette', 'vnd.crick.clicker.template',
            'vnd.crick.clicker.wordbank', 'vnd.criticaltools.wbs+xml',
            'vnd.ctc-posml', 'vnd.ctct.ws+xml', 'vnd.cups-pdf',
            'vnd.cups-postscript', 'vnd.cups-ppd', 'vnd.cups-raster',
            'vnd.cups-raw', 'vnd.curl', 'vnd.cybank',
            'vnd.data-vision.rdz', 'vnd.dece.data', 'vnd.dece.ttml+xml',
            'vnd.dece.unspecified', 'vnd.dece.zip',
            'vnd.denovo.fcselayout-link', 'vnd.dir-bi.plate-dl-nosuffix',
            'vnd.dna', 'vnd.dolby.mobile.1', 'vnd.dolby.mobile.2',
            'vnd.dpgraph', 'vnd.dreamfactory', 'vnd.dvb.ait',
            'vnd.dvb.dvbj', 'vnd.dvb.esgcontainer',
            'vnd.dvb.ipdcdftnotifaccess', 'vnd.dvb.ipdcesgaccess',
            'vnd.dvb.ipdcesgaccess2', 'vnd.dvb.ipdcesgpdd',
            'vnd.dvb.ipdcroaming', 'vnd.dvb.iptv.alfec-base',
            'vnd.dvb.iptv.alfec-enhancement',
            'vnd.dvb.notif-aggregate-root+xml',
            'vnd.dvb.notif-container+xml', 'vnd.dvb.notif-generic+xml',
            'vnd.dvb.notif-ia-msglist+xml',
            'vnd.dvb.notif-ia-registration-request+xml',
            'vnd.dvb.notif-ia-registration-response+xml',
            'vnd.dvb.notif-init+xml', 'vnd.dvb.pfr', 'vnd.dvb.service',
            'vnd.dxr', 'vnd.dynageo', 'vnd.easykaraoke.cdgdownload',
            'vnd.ecdis-update', 'vnd.ecowin.chart',
            'vnd.ecowin.filerequest', 'vnd.ecowin.fileupdate',
            'vnd.ecowin.series', 'vnd.ecowin.seriesrequest',
            'vnd.ecowin.seriesupdate', 'vnd.emclient.accessrequest+xml',
            'vnd.enliven', 'vnd.eprints.data+xml', 'vnd.epson.esf',
            'vnd.epson.msf', 'vnd.epson.quickanime', 'vnd.epson.salt',
            'vnd.epson.ssf', 'vnd.ericsson.quickcall',
            'vnd.eszigno3+xml', 'vnd.etsi.aoc+xml', 'vnd.etsi.cug+xml',
            'vnd.etsi.iptvcommand+xml', 'vnd.etsi.iptvdiscovery+xml',
            'vnd.etsi.iptvprofile+xml', 'vnd.etsi.iptvsad-bc+xml',
            'vnd.etsi.iptvsad-cod+xml', 'vnd.etsi.iptvsad-npvr+xml',
            'vnd.etsi.iptvservice+xml', 'vnd.etsi.iptvsync+xml',
            'vnd.etsi.iptvueprofile+xml', 'vnd.etsi.mcid+xml',
            'vnd.etsi.overload-control-policy-dataset+xml',
            'vnd.etsi.sci+xml', 'vnd.etsi.simservs+xml',
            'vnd.etsi.tsl+xml', 'vnd.etsi.tsl.der', 'vnd.eudora.data',
            'vnd.ezpix-album', 'vnd.ezpix-package',
            'vnd.f-secure.mobile', 'vnd.fdf', 'vnd.fdsn.mseed',
            'vnd.fdsn.seed', 'vnd.ffsns', 'vnd.fints', 'vnd.FloGraphIt',
            'vnd.fluxtime.clip', 'vnd.font-fontforge-sfd',
            'vnd.framemaker', 'vnd.frogans.fnc', 'vnd.frogans.ltf',
            'vnd.fsc.weblaunch', 'vnd.fujitsu.oasys',
            'vnd.fujitsu.oasys2', 'vnd.fujitsu.oasys3',
            'vnd.fujitsu.oasysgp', 'vnd.fujitsu.oasysprs',
            'vnd.fujixerox.ART4', 'vnd.fujixerox.ART-EX',
            'vnd.fujixerox.ddd', 'vnd.fujixerox.docuworks',
            'vnd.fujixerox.docuworks.binder', 'vnd.fujixerox.HBPL',
            'vnd.fut-misnet', 'vnd.fuzzysheet', 'vnd.genomatix.tuxedo',
            'vnd.geocube+xml', 'vnd.geogebra.file', 'vnd.geogebra.tool',
            'vnd.geometry-explorer', 'vnd.geonext', 'vnd.geoplan',
            'vnd.geospace', 'vnd.globalplatform.card-content-mgt',
            'vnd.globalplatform.card-content-mgt-response', 'vnd.gmx',
            'vnd.google-earth.kml+xml', 'vnd.google-earth.kmz',
            'vnd.grafeq', 'vnd.gridmp', 'vnd.groove-account',
            'vnd.groove-help', 'vnd.groove-identity-message',
            'vnd.groove-injector', 'vnd.groove-tool-message',
            'vnd.groove-tool-template', 'vnd.groove-vcard',
            'vnd.hal+json', 'vnd.hal+xml',
            'vnd.HandHeld-Entertainment+xml', 'vnd.hbci',
            'vnd.hcl-bireports', 'vnd.hhe.lesson-player', 'vnd.hp-HPGL',
            'vnd.hp-hpid', 'vnd.hp-hps', 'vnd.hp-jlyt', 'vnd.hp-PCL',
            'vnd.hp-PCLXL', 'vnd.httphone', 'vnd.hydrostatix.sof-data',
            'vnd.hzn-3d-crossword', 'vnd.ibm.afplinedata',
            'vnd.ibm.electronic-media', 'vnd.ibm.MiniPay',
            'vnd.ibm.modcap', 'vnd.ibm.rights-management',
            'vnd.ibm.secure-container', 'vnd.iccprofile', 'vnd.igloader',
            'vnd.immervision-ivp', 'vnd.immervision-ivu',
            'vnd.informedcontrol.rms+xml', 'vnd.infotech.project',
            'vnd.infotech.project+xml', 'vnd.informix-visionary',
            'vnd.insors.igm', 'vnd.intercon.formnet', 'vnd.intergeo',
            'vnd.intertrust.digibox', 'vnd.intertrust.nncp',
            'vnd.intu.qbo', 'vnd.intu.qfx',
            'vnd.iptc.g2.conceptitem+xml',
            'vnd.iptc.g2.knowledgeitem+xml', 'vnd.iptc.g2.newsitem+xml',
            'vnd.iptc.g2.packageitem+xml', 'vnd.ipunplugged.rcprofile',
            'vnd.irepository.package+xml', 'vnd.is-xpr', 'vnd.isac.fcs',
            'vnd.jam', 'vnd.japannet-directory-service',
            'vnd.japannet-jpnstore-wakeup',
            'vnd.japannet-payment-wakeup', 'vnd.japannet-registration',
            'vnd.japannet-registration-wakeup',
            'vnd.japannet-setstore-wakeup', 'vnd.japannet-verification',
            'vnd.japannet-verification-wakeup',
            'vnd.jcp.javame.midlet-rms', 'vnd.jisp',
            'vnd.joost.joda-archive', 'vnd.kahootz', 'vnd.kde.karbon',
            'vnd.kde.kchart', 'vnd.kde.kformula', 'vnd.kde.kivio',
            'vnd.kde.kontour', 'vnd.kde.kpresenter', 'vnd.kde.kspread',
            'vnd.kde.kword', 'vnd.kenameaapp', 'vnd.kidspiration',
            'vnd.Kinar', 'vnd.koan', 'vnd.kodak-descriptor',
            'vnd.las.las+xml', 'vnd.liberty-request+xml',
            'vnd.llamagraphics.life-balance.desktop',
            'vnd.llamagraphics.life-balance.exchange+xml',
            'vnd.lotus-1-2-3', 'vnd.lotus-approach',
            'vnd.lotus-freelance', 'vnd.lotus-notes',
            'vnd.lotus-organizer', 'vnd.lotus-screencam',
            'vnd.lotus-wordpro', 'vnd.macports.portpkg',
            'vnd.marlin.drm.actiontoken+xml',
            'vnd.marlin.drm.conftoken+xml', 'vnd.marlin.drm.license+xml',
            'vnd.marlin.drm.mdcf', 'vnd.mcd', 'vnd.medcalcdata',
            'vnd.mediastation.cdkey', 'vnd.meridian-slingshot',
            'vnd.MFER', 'vnd.mfmp', 'vnd.micrografx.flo',
            'vnd.micrografx.igx', 'vnd.mif', 'vnd.minisoft-hp3000-save',
            'vnd.mitsubishi.misty-guard.trustweb', 'vnd.Mobius.DAF',
            'vnd.Mobius.DIS', 'vnd.Mobius.MBK', 'vnd.Mobius.MQY',
            'vnd.Mobius.MSL', 'vnd.Mobius.PLC', 'vnd.Mobius.TXF',
            'vnd.mophun.application', 'vnd.mophun.certificate',
            'vnd.motorola.flexsuite', 'vnd.motorola.flexsuite.adsi',
            'vnd.motorola.flexsuite.fis', 'vnd.motorola.flexsuite.gotap',
            'vnd.motorola.flexsuite.kmr', 'vnd.motorola.flexsuite.ttc',
            'vnd.motorola.flexsuite.wem', 'vnd.motorola.iprm',
            'vnd.mozilla.xul+xml', 'vnd.ms-artgalry', 'vnd.ms-asf',
            'vnd.ms-cab-compressed', 'vnd.mseq', 'vnd.ms-excel',
            'vnd.ms-excel.addin.macroEnabled.12',
            'vnd.ms-excel.sheet.binary.macroEnabled.12',
            'vnd.ms-excel.sheet.macroEnabled.12',
            'vnd.ms-excel.template.macroEnabled.12', 'vnd.ms-fontobject',
            'vnd.ms-htmlhelp', 'vnd.ms-ims', 'vnd.ms-lrm',
            'vnd.ms-office.activeX+xml', 'vnd.ms-officetheme',
            'vnd.ms-playready.initiator+xml', 'vnd.ms-powerpoint',
            'vnd.ms-powerpoint.addin.macroEnabled.12',
            'vnd.ms-powerpoint.presentation.macroEnabled.12',
            'vnd.ms-powerpoint.slide.macroEnabled.12',
            'vnd.ms-powerpoint.slideshow.macroEnabled.12',
            'vnd.ms-powerpoint.template.macroEnabled.12',
            'vnd.ms-project', 'vnd.ms-tnef', 'vnd.ms-wmdrm.lic-chlg-req',
            'vnd.ms-wmdrm.lic-resp', 'vnd.ms-wmdrm.meter-chlg-req',
            'vnd.ms-wmdrm.meter-resp',
            'vnd.ms-word.document.macroEnabled.12',
            'vnd.ms-word.template.macroEnabled.12', 'vnd.ms-works',
            'vnd.ms-wpl', 'vnd.ms-xpsdocument', 'vnd.msign',
            'vnd.multiad.creator', 'vnd.multiad.creator.cif',
            'vnd.musician', 'vnd.music-niff', 'vnd.muvee.style',
            'vnd.mynfc', 'vnd.ncd.control', 'vnd.ncd.reference',
            'vnd.nervana', 'vnd.netfpx', 'vnd.neurolanguage.nlu',
            'vnd.noblenet-directory', 'vnd.noblenet-sealer',
            'vnd.noblenet-web', 'vnd.nokia.catalogs',
            'vnd.nokia.conml+wbxml', 'vnd.nokia.conml+xml',
            'vnd.nokia.iptv.config+xml', 'vnd.nokia.iSDS-radio-presets',
            'vnd.nokia.landmark+wbxml', 'vnd.nokia.landmark+xml',
            'vnd.nokia.landmarkcollection+xml', 'vnd.nokia.ncd',
            'vnd.nokia.n-gage.ac+xml', 'vnd.nokia.n-gage.data',
            'vnd.nokia.n-gage.symbian.install', 'vnd.nokia.pcd+wbxml',
            'vnd.nokia.pcd+xml', 'vnd.nokia.radio-preset',
            'vnd.nokia.radio-presets', 'vnd.novadigm.EDM',
            'vnd.novadigm.EDX', 'vnd.novadigm.EXT',
            'vnd.ntt-local.file-transfer', 'vnd.ntt-local.sip-ta_remote',
            'vnd.ntt-local.sip-ta_tcp_stream',
            'vnd.oasis.opendocument.chart',
            'vnd.oasis.opendocument.chart-template',
            'vnd.oasis.opendocument.database',
            'vnd.oasis.opendocument.formula',
            'vnd.oasis.opendocument.formula-template',
            'vnd.oasis.opendocument.graphics',
            'vnd.oasis.opendocument.graphics-template',
            'vnd.oasis.opendocument.image',
            'vnd.oasis.opendocument.image-template',
            'vnd.oasis.opendocument.presentation',
            'vnd.oasis.opendocument.presentation-template',
            'vnd.oasis.opendocument.spreadsheet',
            'vnd.oasis.opendocument.spreadsheet-template',
            'vnd.oasis.opendocument.text',
            'vnd.oasis.opendocument.text-master',
            'vnd.oasis.opendocument.text-template',
            'vnd.oasis.opendocument.text-web', 'vnd.obn',
            'vnd.oftn.l10n+json', 'vnd.oipf.contentaccessdownload+xml',
            'vnd.oipf.contentaccessstreaming+xml',
            'vnd.oipf.cspg-hexbinary', 'vnd.oipf.dae.svg+xml',
            'vnd.oipf.dae.xhtml+xml', 'vnd.oipf.mippvcontrolmessage+xml',
            'vnd.oipf.pae.gem', 'vnd.oipf.spdiscovery+xml',
            'vnd.oipf.spdlist+xml', 'vnd.oipf.ueprofile+xml',
            'vnd.oipf.userprofile+xml', 'vnd.olpc-sugar',
            'vnd.oma.bcast.associated-procedure-parameter+xml',
            'vnd.oma.bcast.drm-trigger+xml', 'vnd.oma.bcast.imd+xml',
            'vnd.oma.bcast.ltkm', 'vnd.oma.bcast.notification+xml',
            'vnd.oma.bcast.provisioningtrigger', 'vnd.oma.bcast.sgboot',
            'vnd.oma.bcast.sgdd+xml', 'vnd.oma.bcast.sgdu',
            'vnd.oma.bcast.simple-symbol-container',
            'vnd.oma.bcast.smartcard-trigger+xml',
            'vnd.oma.bcast.sprov+xml', 'vnd.oma.bcast.stkm',
            'vnd.oma.cab-address-book+xml',
            'vnd.oma.cab-feature-handler+xml', 'vnd.oma.cab-pcc+xml',
            'vnd.oma.cab-user-prefs+xml', 'vnd.oma.dcd', 'vnd.oma.dcdc',
            'vnd.oma.dd2+xml', 'vnd.oma.drm.risd+xml',
            'vnd.oma.group-usage-list+xml', 'vnd.oma.pal+xml',
            'vnd.oma.poc.detailed-progress-report+xml',
            'vnd.oma.poc.final-report+xml', 'vnd.oma.poc.groups+xml',
            'vnd.oma.poc.invocation-descriptor+xml',
            'vnd.oma.poc.optimized-progress-report+xml', 'vnd.oma.push',
            'vnd.oma.scidm.messages+xml', 'vnd.oma.xcap-directory+xml',
            'vnd.omads-email+xml', 'vnd.omads-file+xml',
            'vnd.omads-folder+xml', 'vnd.omaloc-supl-init',
            'vnd.oma-scws-config', 'vnd.oma-scws-http-request',
            'vnd.oma-scws-http-response', 'vnd.openofficeorg.extension',
            'vnd.openxmlformats-officedocument.custom-properties+xml',
            'vnd.openxmlformats-officedocument.customXmlProperties+xml',
            'vnd.openxmlformats-officedocument.drawing+xml',
            'vnd.openxmlformats-officedocument.drawingml.chart+xml',
            'vnd.openxmlformats-officedocument.drawingml.chartshapes+xml',
            'vnd.openxmlformats-officedocument.drawingml.diagramColors+xml',
            'vnd.openxmlformats-officedocument.drawingml.diagramData+xml',
            'vnd.openxmlformats-officedocument.drawingml.diagramLayout+xml',
            'vnd.openxmlformats-officedocument.drawingml.diagramStyle+xml',
            'vnd.openxmlformats-officedocument.extended-properties+xml',
            'vnd.openxmlformats-officedocument.presentationml.commentAuthors+xml',
            'vnd.openxmlformats-officedocument.presentationml.comments+xml',
            'vnd.openxmlformats-officedocument.presentationml.handoutMaster+xml',
            'vnd.openxmlformats-officedocument.presentationml.notesMaster+xml',
            'vnd.openxmlformats-officedocument.presentationml.notesSlide+xml',
            'vnd.openxmlformats-officedocument.presentationml.presentation',
            'vnd.openxmlformats-officedocument.presentationml.presentation.main+xml',
            'vnd.openxmlformats-officedocument.presentationml.presProps+xml',
            'vnd.openxmlformats-officedocument.presentationml.slide',
            'vnd.openxmlformats-officedocument.presentationml.slide+xml',
            'vnd.openxmlformats-officedocument.presentationml.slideLayout+xml',
            'vnd.openxmlformats-officedocument.presentationml.slideMaster+xml',
            'vnd.openxmlformats-officedocument.presentationml.slideshow',
            'vnd.openxmlformats-officedocument.presentationml.slideshow.main+xml',
            'vnd.openxmlformats-officedocument.presentationml.slideUpdateInfo+xml',
            'vnd.openxmlformats-officedocument.presentationml.tableStyles+xml',
            'vnd.openxmlformats-officedocument.presentationml.tags+xml',
            'vnd.openxmlformats-officedocument.presentationml.template',
            'vnd.openxmlformats-officedocument.presentationml.template.main+xml',
            'vnd.openxmlformats-officedocument.presentationml.viewProps+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.calcChain+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.chartsheet+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.comments+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.connections+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.dialogsheet+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.externalLink+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.pivotCacheDefinition+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.pivotCacheRecords+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.pivotTable+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.queryTable+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.revisionHeaders+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.revisionLog+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.sheetMetadata+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.styles+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.table+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.tableSingleCells+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.template',
            'vnd.openxmlformats-officedocument.spreadsheetml.template.main+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.userNames+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.volatileDependencies+xml',
            'vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml',
            'vnd.openxmlformats-officedocument.theme+xml',
            'vnd.openxmlformats-officedocument.themeOverride+xml',
            'vnd.openxmlformats-officedocument.vmlDrawing',
            'vnd.openxmlformats-officedocument.wordprocessingml.comments+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.document',
            'vnd.openxmlformats-officedocument.wordprocessingml.document.glossary+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.endnotes+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.fontTable+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.footer+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.settings+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.styles+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.template',
            'vnd.openxmlformats-officedocument.wordprocessingml.template.main+xml',
            'vnd.openxmlformats-officedocument.wordprocessingml.webSettings+xml',
            'vnd.openxmlformats-package.core-properties+xml',
            'vnd.openxmlformats-package.digital-signature-xmlsignature+xml',
            'vnd.openxmlformats-package.relationships+xml',
            'vnd.osa.netdeploy', 'vnd.osgeo.mapguide.package',
            'vnd.osgi.bundle', 'vnd.osgi.dp', 'vnd.otps.ct-kip+xml',
            'vnd.palm', 'vnd.paos.xml', 'vnd.pawaafile', 'vnd.pg.format',
            'vnd.pg.osasli', 'vnd.piaccess.application-licence',
            'vnd.picsel', 'vnd.pmi.widget',
            'vnd.poc.group-advertisement+xml', 'vnd.pocketlearn',
            'vnd.powerbuilder6', 'vnd.powerbuilder6-s',
            'vnd.powerbuilder7', 'vnd.powerbuilder75',
            'vnd.powerbuilder75-s', 'vnd.powerbuilder7-s',
            'vnd.preminet', 'vnd.previewsystems.box',
            'vnd.proteus.magazine', 'vnd.publishare-delta-tree',
            'vnd.pvi.ptid1', 'vnd.pwg-multiplexed',
            'vnd.pwg-xhtml-print+xml', 'vnd.qualcomm.brew-app-res',
            'vnd.Quark.QuarkXPress', 'vnd.quobject-quoxdocument',
            'vnd.radisys.moml+xml', 'vnd.radisys.msml-audit-conf+xml',
            'vnd.radisys.msml-audit-conn+xml',
            'vnd.radisys.msml-audit-dialog+xml',
            'vnd.radisys.msml-audit-stream+xml',
            'vnd.radisys.msml-audit+xml', 'vnd.radisys.msml-conf+xml',
            'vnd.radisys.msml-dialog-base+xml',
            'vnd.radisys.msml-dialog-fax-detect+xml',
            'vnd.radisys.msml-dialog-fax-sendrecv+xml',
            'vnd.radisys.msml-dialog-group+xml',
            'vnd.radisys.msml-dialog-speech+xml',
            'vnd.radisys.msml-dialog-transform+xml',
            'vnd.radisys.msml-dialog+xml', 'vnd.radisys.msml+xml',
            'vnd.rainstor.data', 'vnd.rapid', 'vnd.realvnc.bed',
            'vnd.recordare.musicxml', 'vnd.recordare.musicxml+xml',
            'vnd.RenLearn.rlprint', 'vnd.rig.cryptonote',
            'vnd.route66.link66+xml', 'vnd.ruckus.download', 'vnd.s3sms',
            'vnd.sailingtracker.track', 'vnd.sbm.cid', 'vnd.sbm.mid2',
            'vnd.scribus', 'vnd.sealed.3df', 'vnd.sealed.csf',
            'vnd.sealed.doc', 'vnd.sealed.eml', 'vnd.sealed.mht',
            'vnd.sealed.net', 'vnd.sealed.ppt', 'vnd.sealed.tiff',
            'vnd.sealed.xls', 'vnd.sealedmedia.softseal.html',
            'vnd.sealedmedia.softseal.pdf', 'vnd.seemail', 'vnd.sema',
            'vnd.semd', 'vnd.semf', 'vnd.shana.informed.formdata',
            'vnd.shana.informed.formtemplate',
            'vnd.shana.informed.interchange',
            'vnd.shana.informed.package', 'vnd.SimTech-MindMapper',
            'vnd.smaf', 'vnd.smart.notebook', 'vnd.smart.teacher',
            'vnd.software602.filler.form+xml',
            'vnd.software602.filler.form-xml-zip', 'vnd.solent.sdkm+xml',
            'vnd.spotfire.dxp', 'vnd.spotfire.sfs', 'vnd.sss-cod',
            'vnd.sss-dtf', 'vnd.sss-ntf', 'vnd.stepmania.package',
            'vnd.stepmania.stepchart', 'vnd.street-stream',
            'vnd.sun.wadl+xml', 'vnd.sus-calendar', 'vnd.svd',
            'vnd.swiftview-ics', 'vnd.syncml.dm.notification',
            'vnd.syncml.dm+wbxml', 'vnd.syncml.dm+xml',
            'vnd.syncml.ds.notification', 'vnd.syncml+xml',
            'vnd.tao.intent-module-archive', 'vnd.tcpdump.pcap',
            'vnd.tmobile-livetv', 'vnd.trid.tpt', 'vnd.triscape.mxs',
            'vnd.trueapp', 'vnd.truedoc', 'vnd.ubisoft.webplayer',
            'vnd.ufdl', 'vnd.uiq.theme', 'vnd.umajin', 'vnd.unity',
            'vnd.uoml+xml', 'vnd.uplanet.alert',
            'vnd.uplanet.alert-wbxml', 'vnd.uplanet.bearer-choice',
            'vnd.uplanet.bearer-choice-wbxml', 'vnd.uplanet.cacheop',
            'vnd.uplanet.cacheop-wbxml', 'vnd.uplanet.channel',
            'vnd.uplanet.channel-wbxml', 'vnd.uplanet.list',
            'vnd.uplanet.listcmd', 'vnd.uplanet.listcmd-wbxml',
            'vnd.uplanet.list-wbxml', 'vnd.uplanet.signal', 'vnd.vcx',
            'vnd.vd-study', 'vnd.vectorworks', 'vnd.verimatrix.vcas',
            'vnd.vidsoft.vidconference', 'vnd.visio', 'vnd.visionary',
            'vnd.vividence.scriptfile', 'vnd.vsf', 'vnd.wap.sic',
            'vnd.wap.slc', 'vnd.wap.wbxml', 'vnd.wap.wmlc',
            'vnd.wap.wmlscriptc', 'vnd.webturbo', 'vnd.wfa.wsc',
            'vnd.wmc', 'vnd.wmf.bootstrap', 'vnd.wolfram.mathematica',
            'vnd.wolfram.mathematica.package', 'vnd.wolfram.player',
            'vnd.wordperfect', 'vnd.wqd', 'vnd.wrq-hp3000-labelled',
            'vnd.wt.stf', 'vnd.wv.csp+xml', 'vnd.wv.csp+wbxml',
            'vnd.wv.ssp+xml', 'vnd.xara', 'vnd.xfdl', 'vnd.xfdl.webform',
            'vnd.xmi+xml', 'vnd.xmpie.cpkg', 'vnd.xmpie.dpkg',
            'vnd.xmpie.plan', 'vnd.xmpie.ppkg', 'vnd.xmpie.xlim',
            'vnd.yamaha.hv-dic', 'vnd.yamaha.hv-script',
            'vnd.yamaha.hv-voice',
            'vnd.yamaha.openscoreformat.osfpvg+xml',
            'vnd.yamaha.openscoreformat', 'vnd.yamaha.remote-setup',
            'vnd.yamaha.smaf-audio', 'vnd.yamaha.smaf-phrase',
            'vnd.yamaha.through-ngn', 'vnd.yamaha.tunnel-udpencap',
            'vnd.yellowriver-custom-menu', 'vnd.zul',
            'vnd.zzazz.deck+xml', 'voicexml+xml', 'vq-rtcpxr',
            'watcherinfo+xml', 'whoispp-query', 'whoispp-response',
            'widget', 'wita', 'wordperfect5.1', 'wsdl+xml',
            'wspolicy+xml', 'x400-bp', 'xcap-att+xml', 'xcap-caps+xml',
            'xcap-diff+xml', 'xcap-el+xml', 'xcap-error+xml',
            'xcap-ns+xml', 'xcon-conference-info-diff+xml',
            'xcon-conference-info+xml', 'xenc+xml', 'xhtml-voice+xml',
            'xhtml+xml', 'xml', 'xml-dtd', 'xml-external-parsed-entity',
            'xmpp+xml', 'xop+xml', 'xslt+xml', 'xv+xml', 'yang',
            'yin+xml', 'zip'
        ],

        'audio' => [

            '1d-interleaved-parityfec', '32kadpcm', '3gpp',
            '3gpp2', 'ac3', 'AMR', 'AMR-WB', 'amr-wb+', 'asc',
            'ATRAC-ADVANCED-LOSSLESS', 'ATRAC-X', 'ATRAC3', 'basic',
            'BV16', 'BV32', 'clearmode', 'CN', 'DAT12', 'dls',
            'dsr-es201108', 'dsr-es202050', 'dsr-es202211',
            'dsr-es202212', 'eac3', 'DVI4', 'EVRC', 'EVRC0', 'EVRC1',
            'EVRCB', 'EVRCB0', 'EVRCB1', 'EVRC-QCP', 'EVRCWB', 'EVRCWB0',
            'EVRCWB1', 'example', 'fwdred', 'G719', 'G722', 'G7221',
            'G723', 'G726-16', 'G726-24', 'G726-32', 'G726-40', 'G728',
            'G729', 'G7291', 'G729D', 'G729E', 'GSM', 'GSM-EFR',
            'GSM-HR-08', 'iLBC', 'ip-mr_v2.5', 'L8', 'L16', 'L20', 'L24',
            'LPC', 'mobile-xmf', 'MPA', 'mp4', 'MP4A-LATM', 'mpa-robust',
            'mpeg', 'mpeg4-generic', 'ogg', 'parityfec', 'PCMA',
            'PCMA-WB', 'PCMU', 'PCMU-WB', 'prs.sid', 'QCELP', 'RED',
            'rtp-enc-aescm128', 'rtp-midi', 'rtx', 'SMV', 'SMV0',
            'SMV-QCP', 'sp-midi', 'speex', 't140c', 't38',
            'telephone-event', 'tone', 'UEMCLIP', 'ulpfec', 'VDVI',
            'VMR-WB', 'vnd.3gpp.iufp', 'vnd.4SB', 'vnd.audiokoz',
            'vnd.CELP', 'vnd.cisco.nse', 'vnd.cmles.radio-events',
            'vnd.cns.anp1', 'vnd.cns.inf1', 'vnd.dece.audio',
            'vnd.digital-winds', 'vnd.dlna.adts', 'vnd.dolby.heaac.1',
            'vnd.dolby.heaac.2', 'vnd.dolby.mlp', 'vnd.dolby.mps',
            'vnd.dolby.pl2', 'vnd.dolby.pl2x', 'vnd.dolby.pl2z',
            'vnd.dolby.pulse.1', 'vnd.dra', 'vnd.dts', 'vnd.dts.hd',
            'vnd.dvb.file', 'vnd.everad.plj', 'vnd.hns.audio',
            'vnd.lucent.voice', 'vnd.ms-playready.media.pya',
            'vnd.nokia.mobile-xmf', 'vnd.nortel.vbk',
            'vnd.nuera.ecelp4800', 'vnd.nuera.ecelp7470',
            'vnd.nuera.ecelp9600', 'vnd.octel.sbc', 'vnd.qcelp',
            'vnd.rhetorex.32kadpcm', 'vnd.rip',
            'vnd.sealedmedia.softseal.mpeg', 'vnd.vmx.cvsd', 'vorbis',
        ],

        'font' => [
            'collection', 'otf', 'sfnt', 'ttf', 'woff', 'woff2'
        ],

        'image' => [

            'cgm', 'example', 'fits', 'g3fax', 'gif', 'ief',
            'jp2', 'jpeg', 'jpm', 'jpx', 'ktx', 'naplps', 'png',
            'prs.btif', 'prs.pti', 'svg+xml', 't38', 'tiff', 'tiff-fx',
            'vnd.adobe.photoshop', 'vnd.cns.inf2', 'vnd.dece.graphic',
            'vnd.djvu', 'vnd.dwg', 'vnd.dxf', 'vnd.dvb.subtitle',
            'vnd.fastbidsheet', 'vnd.fpx', 'vnd.fst',
            'vnd.fujixerox.edmics-mmr', 'vnd.fujixerox.edmics-rlc',
            'vnd.globalgraphics.pgb', 'vnd.microsoft.icon', 'vnd.mix',
            'vnd.ms-modi', 'vnd.net-fpx', 'vnd.radiance',
            'vnd.sealed.png', 'vnd.sealedmedia.softseal.gif',
            'vnd.sealedmedia.softseal.jpg', 'vnd.svf', 'vnd.wap.wbmp',
            'vnd.xiff'
        ],

        'message' => [

            'CPIM', 'delivery-status',
            'disposition-notification', 'example', 'external-body',
            'feedback-report', 'global', 'global-delivery-status',
            'global-disposition-notification', 'global-headers', 'http',
            'imdn+xml', 'news', 'partial', 'rfc822', 's-http', 'sip',
            'sipfrag', 'tracking-status', 'vnd.si.simp'
        ],

        'model' => [

            'example', 'iges', 'mesh', 'vnd.collada+xml',
            'vnd.dwf', 'vnd.flatland.3dml', 'vnd.gdl', 'vnd.gs-gdl',
            'vnd.gtw', 'vnd.moml+xml', 'vnd.mts',
            'vnd.parasolid.transmit.binary',
            'vnd.parasolid.transmit.text', 'vnd.vtu', 'vrml'
        ],

        'multipart' => [

            'alternative', 'appledouble', 'byteranges',
            'digest', 'encrypted', 'example', 'form-data', 'header-set',
            'mixed', 'parallel', 'related', 'report', 'signed',
            'voice-message'
        ],

        'text' => [

            '1d-interleaved-parityfec', 'calendar', 'css',
            'csv', 'directory', 'dns', 'ecmascript', 'enriched',
            'example', 'fwdred', 'html', 'javascript (obsolete)', 'n3',
            'parityfec', 'plain', 'prs.fallenstein.rst', 'prs.lines.tag',
            'RED', 'rfc822-headers', 'richtext', 'rtf',
            'rtp-enc-aescm128', 'rtx', 'sgml', 't140',
            'tab-separated-values', 'troff', 'turtle', 'ulpfec',
            'uri-list', 'vcard', 'vnd.abc', 'vnd.curl',
            'vnd.DMClientScript', 'vnd.dvb.subtitle',
            'vnd.esmertec.theme-descriptor', 'vnd.fly',
            'vnd.fmi.flexstor', 'vnd.graphviz', 'vnd.in3d.3dml',
            'vnd.in3d.spot', 'vnd.IPTC.NewsML', 'vnd.IPTC.NITF',
            'vnd.latex-z', 'vnd.motorola.reflex', 'vnd.ms-mediapackage',
            'vnd.net2phone.commcenter.command',
            'vnd.radisys.msml-basic-layout', 'vnd.si.uricatalogue',
            'vnd.sun.j2me.app-descriptor', 'vnd.trolltech.linguist',
            'vnd.wap.si', 'vnd.wap.sl', 'vnd.wap.wml',
            'vnd.wap.wmlscript', 'xml', 'xml-external-parsed-entity'
        ],

        'video' => [

            '1d-interleaved-parityfec', '3gpp', '3gpp2',
            '3gpp-tt', 'BMPEG', 'BT656', 'CelB', 'DV', 'example', 'H261',
            'H263', 'H263-1998', 'H263-2000', 'H264', 'H264-RCDO',
            'H264-SVC', 'JPEG', 'jpeg2000', 'MJ2', 'MP1S', 'MP2P',
            'MP2T', 'mp4', 'MP4V-ES', 'MPV', 'mpeg', 'mpeg4-generic',
            'nv', 'ogg', 'parityfec', 'pointer', 'quicktime', 'raw',
            'rtp-enc-aescm128', 'rtx', 'SMPTE292M', 'ulpfec', 'vc1',
            'vnd.CCTV', 'vnd.dece.hd', 'vnd.dece.mobile', 'vnd.dece.mp4',
            'vnd.dece.pd', 'vnd.dece.sd', 'vnd.dece.video',
            'vnd.directv.mpeg', 'vnd.directv.mpeg-tts',
            'vnd.dlna.mpeg-tts', 'vnd.dvb.file', 'vnd.fvt',
            'vnd.hns.video', 'vnd.iptvforum.1dparityfec-1010',
            'vnd.iptvforum.1dparityfec-2005',
            'vnd.iptvforum.2dparityfec-1010',
            'vnd.iptvforum.2dparityfec-2005', 'vnd.iptvforum.ttsavc',
            'vnd.iptvforum.ttsmpeg2', 'vnd.motorola.video',
            'vnd.motorola.videop', 'vnd.mpegurl',
            'vnd.ms-playready.media.pyv',
            'vnd.nokia.interleaved-multimedia', 'vnd.nokia.videovoip',
            'vnd.objectvideo', 'vnd.sealed.mpeg1', 'vnd.sealed.mpeg4',
            'vnd.sealed.swf', 'vnd.sealedmedia.softseal.mov',
            'vnd.uvvu.mp4', 'vnd.vivo'
        ]
    ];

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    // Validator Interface Interface Methods

    /**
     * Validates given MIME Type
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() : bool {

        // Input string must have a slash

        if( strpos( $this -> options -> value, '/' ) === FALSE ) {
            return FALSE;
        }

        $data = explode( '/', $this -> options -> value );

        // Splitting operation must result a two-indexes array

        if( count( $data ) != 2 ) Return FALSE;

        // Second index must reflect one of valid parts of MIME Types (defined in $mimes keys)

        if( ! array_key_exists( $data[ 0 ], self::MIMES_LIST ) ) {
            return FALSE;
        }

        return in_array( $data[ 1 ], self::MIMES_LIST[ $data[ 0 ] ] );
    }
}
