let scores_data;

$(document).ready(function() {
  $('.ui.dropdown').dropdown();
  $('.sidebar-menu-toggler').on('click', function() {
    var target = $(this).data('target');
    $(target)
      .sidebar({
        dinPage: true,
        transition: 'overlay',
        mobileTransition: 'overlay'
      })
      .sidebar('toggle');
  });
});

function cleanScoreData() {
  for (row of scores_data) {
    row.year = Number(row.year);
    row.total = 0;
    row.exams = [];

    for (e of ["five_no_exams", "four_no_exams", "three_no_exams", "two_no_exams", "one_no_exams"]) {
      row.total += row[e] = Number(row[e].replace(/,/g, ''));
      row.exams.push({
        category: e,
        value: row[e]
      })
    }

    row.avg = 0;
    row.avg += 5 * row.five_no_exams;
    row.avg += 4 * row.four_no_exams;
    row.avg += 3 * row.three_no_exams;
    row.avg += 2 * row.two_no_exams;
    row.avg += 1 * row.one_no_exams;
    row.avg /= row.total;
  }
}

function cleanTypeData() {
  for (row of wisc_data) {
    row.schools = []
    for (e of ["public", "nonpublic"]) {
      row.schools.push({
        year: row.year,
        category: e,
        value: row[e]
      })
    }
  }
}

$(document).ready(() => {
  d3.csv("data/scores.csv").then((data) => {
    scores_data = data;
    cleanScoreData();
    makeScoresGraph(scores_data);
  })

  d3.csv("data/wiscbytyp.csv").then((data) => {
    wisc_data = data;
    cleanTypeData();
    makeTypGraph(wisc_data);
  })

  var selected = $("#page").attr("value");
  $(`#${selected}`).addClass("active")
});