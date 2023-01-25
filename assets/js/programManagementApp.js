let dailyProgramListElt = document.getElementsByClassName('daily-program-list').length > 0;

if (dailyProgramListElt) {
    const programDisplayer = new ProgramDisplayer;
    programDisplayer.init();
}

else {
    const programBuildingHelper = new ProgramBuildingHelper;
    programBuildingHelper.init();
}