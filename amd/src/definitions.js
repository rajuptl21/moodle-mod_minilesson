define(['jquery','core/log'], function($,log) {
    "use strict"; // jshint ;_;

/*
This file contains class and ID definitions.
 */

    log.debug('MiniLesson definitions: initialising');

    return{
        component: 'mod_minilesson',
        componentpath: 'mod/minilesson',
        quizcontainer: 'mod_minilesson_quiz_cont',

        //player code
        hiddenplayer: 'mod_minilesson_hidden_player',
        hiddenplayerbutton: 'mod_minilesson_hidden_player_button',
        hiddenplayerbuttonactive: 'mod_minilesson_hidden_player_button_active',
        hiddenplayerbuttonpaused: 'mod_minilesson_hidden_player_button_paused',
        hiddenplayerbuttonplaying: 'mod_minilesson_hidden_player_button_playing',
        qr_player: 'mod_minilesson_qr_player',

        //popover
        okbuttonclass: 'mod_minilesson_quickgrade_ok',
        ngbuttonclass: 'mod_minilesson_quickgrade_ng',
        quickgradecontainerclass: 'mod_minilesson_quickgrade_cont',


        //rsquestions
        noitemscontainer: 'mod_minilesson_noitems_cont',
        itemscontainer: 'mod_minilesson_items_cont',
        itemstable: 'mod_minilesson_qpanel',
        itemrow: 'mod_minilesson_item_row',
        movearrow: 'mod_minilesson_item_move',

        //grade now
        passagecontainer: 'mod_minilesson_grading_passagecont',
        audioplayerclass: 'mod_minilesson_grading_player',
        wordplayerclass: 'mod_minilesson_hidden_player',
        wordclass: 'mod_minilesson_grading_passageword',
        spaceclass: 'mod_minilesson_grading_passagespace',
        badwordclass: 'mod_minilesson_grading_badword',
        endspaceclass: 'mod_minilesson_grading_endspace',
        unreadwordclass:  'mod_minilesson_grading_unreadword',
        unreadspaceclass: 'mod_minilesson_grading_unreadspace',
        modebutton: 'mod_minilesson_modebutton',

        //activity

        gradingmodebutton: 'mod_minilesson_gradingbutton',
        clearbutton: 'mod_minilesson_clearbutton',


        //quiz
        qtype_pictureprompt: 'multichoicepicture',
        qtype_audioprompt: 'multichoiceaudio',
        qtype_textpromptlong: 'multichoicelong',
        qtype_textpromptshort: 'multichoice',
        qtype_textpromptaudio: 'audioresponse',

        //question types
        qtype_page: 'page',
        qtype_multichoice: 'multichoice',
        qtype_multiaudio: 'multiaudio',
        qtype_dictationchat: 'dictationchat',
        qtype_dictation: 'dictation',
        qtype_speechcards: 'speechcards',
        qtype_listenrepeat: 'listenrepeat',
        qtype_smartframe: 'smartframe',
        qtype_shortanswer: 'shortanswer',
        qtype_listeninggapfill: 'listeninggapfill',
        qtype_speakinggapfill: 'speakinggapfill',
        qtype_typinggapfill: 'typinggapfill',
        qtype_spacegame: 'spacegame',
        qtype_fluency: 'fluency',
        qtype_freespeaking: 'freespeaking',
        qtype_freewriting: 'freewriting',
        qtype_passagereading: 'passagereading',
        qtype_h5p: 'h5p',
        qtype_conversation: 'conversation',
        qtype_compquiz: 'compquiz',
        qtype_passagegapfill: 'passagegapfill',

        //running records features
        maybeselfcorrectedwordclass: 'mod_minilesson_grading_maybeselfcorrectedword',
        selfcorrectedwordclass: 'mod_minilesson_grading_selfcorrectedword',
        structuralclass: 'mod_minilesson_grading_structural',
        meaningclass: 'mod_minilesson_grading_meaning',
        visualclass: 'mod_minilesson_grading_visual',
        notesclass: 'mod_minilesson_notes',

        //modes
        modegrading: 'grading',
        modespotcheck: 'spotcheck',
        modetranscript: 'transcript',
        modemsv: 'msv',

        //MSV stuff
        msvclosebuttonclass: 'mod_minilesson_msvgrade_close',
        s_buttonclass: 'mod_minilesson_msv_s',
        m_buttonclass: 'mod_minilesson_msv_m',
        v_buttonclass: 'mod_minilesson_msv_v',
        correctbuttonclass: 'mod_minilesson_msv_correct',
        errorbuttonclass: 'mod_minilesson_msv_error',
        selfcorrectbuttonclass: 'mod_minilesson_msv_selfcorrect',
        msvcontainer: 'mod_minilesson_msv_cont',
        msvmodebutton: 'mod_minilesson_msvbutton',
        msvgradebutton: 'mod_minilesson_msvgrade_msv',
        msvmode: 'mod_minilesson_msvmode',
        stateerror: 'stateerror',
        statecorrect: 'statecorrect',
        stateselfcorrect: 'stateselfcorrect',
        formelementnotes: 'mod_minilesson_grading_form_notes',
        formelementselfcorrections: 'mod_minilesson_grading_form_selfcorrections',
        gradingmode: 'mod_minilesson_gradingmode',
        transcriptmode: 'mod_minilesson_transcriptmode',
        msvcontainerclass: 'mod_minilesson_msvcontainer',
        msvbuttonsbox: 'mod_minilesson_msvbuttonsbox',

        //VOICES
        voices: {'ar-AR': ['Zeina','Hala','Zayd'],
            'de-DE': ['Hans','Marlene','Vicki'],
            'en-US': ['Joey','Justin','Kevin','Matthew','Ivy','Joanna','Kendra','Kimberly','Salli'],
            'en-GB': ['Brian','Amy', 'Emma','Arthur'],
            'en-AU': ['Russell','Nicole','Olivia'],
            'en-NZ': ['Aria'],
            'en-ZA': ['Ayanda'],
            'en-IN': ['Aditi','Raveena'],
            'en-WL': ["Geraint"],
            'es-US': ['Miguel','Penelope','Lupe','Pedro'],
            'es-ES': [ 'Enrique','Conchita','Lucia'],
            'fr-CA': ['Chantal','Gabrielle'],
            'fr-FR': ['Mathieu','Celine','Lea','Remi'],
            'hi-IN': ["Aditi"],
            'it-IT': ['Carla','Bianca','Giorgio'],
            'ja-JP': ['Takumi','Mizuki','Kazuha','Tomoko'],
            'ko-KR': ['Seoyeon'],
            'nl-BE': ["Lisa"],
            'nl-NL': ["Ruben","Lotte"],
            'pt-BR': ['Ricardo','Vitoria'],
            'pt-PT': ["Ines",'Cristiano'],
            'ru-RU': ["Tatyana","Maxim"],
            'tr-TR': ['Filiz'],
            'zh-CN': ['Zhiyu']
        },

        neural_voices: ["Amy","Emma","Brian","Olivia","Aria","Ayanda","Ivy","Joanna","Kendra","Kimberly",
            "Salli","Joey","Justin","Kevin","Matthew","Camila","Lupe","Lucia","Gabrielle","Lea", "Vicki", "Seoyeon", "Takumi","Lucia",
            "Lea","Bianca","Laura","Kajal","Suvi","Liam","Daniel","Hannah","Camila","Ida","Kazuha","Tomoko","Elin","Hala","Zayd"]

    };//end of return value
});