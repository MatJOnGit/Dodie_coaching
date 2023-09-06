"use strict";

var dailyProgramListElt = document.getElementsByClassName('daily-program-list').length > 0;
if (dailyProgramListElt) {
  var programDisplayer = new ProgramDisplayer();
  programDisplayer.init();
} else {
  var programBuildingHelper = new ProgramBuildingHelper();
  programBuildingHelper.init();
}
