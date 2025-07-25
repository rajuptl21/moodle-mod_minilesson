define(['jquery',
   'core/log',
   'mod_minilesson/definitions',
   'core/templates'
  ], function($, log, def, templates) {
  "use strict"; // jshint ;_;

  /*
  This file is to manage the quiz stage
   */

  log.debug('MiniLesson dictation: initialising');

  return {

    playing: false,

    //for making multiple instances
    clone: function () {
        return $.extend(true, {}, this);
    },

    init: function(index, itemdata, quizhelper, polly) {
      var self = this;
      self.itemdata = itemdata;
      self.quizhelper = quizhelper;
      self.index = index;

      self.prepare_audio(itemdata);
      self.register_events(index, itemdata, quizhelper);
      self.getItems();
    },

    getItems: function() {
      var self = this;
      var text_items = self.itemdata.sentences;

      self.items = text_items.map(function(target) {
        return {
          landr_targetWords: target.sentence.trim().split(self.quizhelper.spliton_regexp).filter(function(e) {
            return e !== "";
          }),
          target: target.sentence,
          audio: {},
          audiourl: target.audiourl ? target.audiourl : "",
          imageurl: target.imageurl,
          correct: false,
        };
      }).filter(function(e) {
        return e.target !== "";
      });
    },

    prepare_html: function(itemdata) {
      //do something
    },

    prepare_audio: function(itemdata) {
      $.each(itemdata.sentences, function(index, sentence) {
          $("#" + itemdata.uniqueid + "_container .dictationplayer_" + index + " .dictationtrigger").attr("data-src", sentence.audiourl);
      });
    },

    show_item_review:function(){
      var self=this;
      //build review data
      var review_data = {};
      review_data.correctitems = $('#' + self.itemdata.uniqueid + '_container .dictate-feedback.fa-check').length;
      review_data.totalitems = $('#' + self.itemdata.uniqueid + '_container .dictate-feedback').length;
      var rows = $('#' + self.itemdata.uniqueid + '_container .dictationrow');
      rows.each(function(index){
        self.items[index].audio.src = $(this).find('.dictationtrigger').attr('data-src');
        self.items[index].correct = $(this).find('.dictate-feedback').hasClass('fa-check');
      });
      review_data.items = self.items;


      //display results
      var gamebox= $("#" + self.itemdata.uniqueid + "_container .ml_dictation_rows");
      var resultsbox = $("#" + self.itemdata.uniqueid + "_container .ml_dictation_resultscontainer");
      templates.render('mod_minilesson/listitemresults',review_data).then(
        function(html,js){
            resultsbox.html(html);
            //show and hide
            resultsbox.show();
            gamebox.hide();
            // Run js for audio player events
            templates.runTemplateJS(js);
        }
      );// End of templates
    },

    next_question:function(){
      var self=this;
      var stepdata = {};
      var correct = $('#' + self.itemdata.uniqueid + '_container .dictate-feedback.fa-check').length;
      var total = $('#' + self.itemdata.uniqueid + '_container .dictate-feedback').length;
      var grade = Math.round(correct / total, 2) * 100;
      stepdata.index = self.index;
      stepdata.hasgrade = true;
      stepdata.grade = grade;
      stepdata.totalitems=total;
      stepdata.correctitems=correct;
      stepdata.grade = grade;
      self.quizhelper.do_next(stepdata);
    },

    register_events: function(qindex, itemdata, quizhelper) {

      var self = this;

      var theplayer = $("#" + itemdata.uniqueid + "_player");

      //key events in text box
      $("#" + itemdata.uniqueid + "_container .poodlldictationinput input").on("input", function(e) {

        var index = $(this).data("index");
        var correct = itemdata.sentences[index].sentence.trim().toLowerCase();
        var typed = $(this).val().trim().toLowerCase();

        //update char count
        $("#"+itemdata.uniqueid+"_container .dictationplayer_"+index+"_chars").html(typed.length);
        //trim punctuation before comparing, if ignore punctuation is set
        if(itemdata.ignorepunctuation){
            correct = quizhelper.cleanText(correct);
            typed = quizhelper.cleanText(typed);
        }

        if (correct == typed) {
          $("#"+itemdata.uniqueid+"_container .dictate-feedback[data-index='" + index + "']").removeClass("fa-times").addClass("fa-check").css("color","green").show();
        } else {
          $("#"+itemdata.uniqueid+"_container .dictate-feedback[data-index='" + index + "']").removeClass("fa-check").addClass("fa-times").css("color","red").show();
        }

      });

      //audio play requests
      $("#" + itemdata.uniqueid + "_container .dictationtrigger").on('click', function(e) {
        if (!self.playing) {
          var el = this;
          self.playing = true;
          theplayer.attr('src', $(this).attr('data-src'));
          theplayer[0].play();
          theplayer[0].onended = function() {
            $(el).find(".fa").removeClass("fa-spin fa-spinner").addClass("fa-play");
            self.playing = false;
          };
          $(el).find(".fa").removeClass("fa-play").addClass("fa-spin fa-spinner");
        }
      });

      //When click next button , report and leave it up to parent to eal with it.
      $("#" + itemdata.uniqueid + "_container .minilesson_nextbutton").on('click', function(e) {
        var dictationcontainer = $("#" + self.itemdata.uniqueid + "_container .ml_dictation_rows");
        if(self.quizhelper.showitemreview && dictationcontainer.is(':visible')){
          self.show_item_review();
        }else{
          self.next_question();
        }
      });
    }
  }; //end of return value
});