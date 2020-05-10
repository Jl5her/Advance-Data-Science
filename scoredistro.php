<?php require_once("include/header.php"); ?>
<title>Number of Exams By Score</title>
<meta id="page" value="distro"></meta>

<b>Highlight Score</b>  
<select id="selectButton"></select>

<h4>Filter Year</h4>
<div class="ui labeled ticked range slider" id="slider-range"></div>


<svg id="line" width="1000" height="500"></svg>



<?php require_once("include/footer.php"); ?>

<script>
let first, second;
const margin = {
  top: 20,
  right: 75,
  bottom: 75,
  left: 75
}

function makeScoresGraph(data) {
  const svg = d3.select(`svg`),
  width = +svg.attr("width") - margin.left - margin.right,
  height = +svg.attr("height") - margin.top - margin.bottom,
  g = svg.append("g").attr("transform", `translate(${margin.left}, ${margin.top})`);

    var groups = [
      {friendly: "5", key: "five_no_exams"},
      {friendly: "4", key: "four_no_exams"},
      {friendly: "3", key: "three_no_exams"},
      {friendly: "2", key: "two_no_exams"},
      {friendly: "1", key: "one_no_exams"}
    ]

  d3.select("#selectButton")
    .selectAll('myOptions')
    .data(groups)
    .enter()
    .append('option')
    .text(function (d) { return d.friendly; })
    .attr("value", function (d) { return d.key; })

    first = d3.min(data, d => d.year);
    second = d3.max(data, d => d.year);

    $('.ui.range.slider')
      .slider({
        min: first,
        max: second,
        start: first,
        end: second,
      })

    var x = d3.scaleBand()
      .rangeRound([0, width])
      .domain(data.map(d => d.year))
      .range([ 0, width ])
      .padding(0.1)

    const xAxis = g.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x))
    
    xAxis.append("text")
      .attr("fill", "#000")
      .attr("y", 6)
      .attr("dy", "3em")
      .attr("dx",  width / 2)
      .attr("text-anchor", "middle")
      .text("Year");

    var y = d3.scaleLinear()
      .domain( [0, d3.max(data, d => d3.max(d.exams, s => s.value))])
      .range([ height, 0 ]);
    g.append("g")
      .call(d3.axisLeft(y)
        .tickSize(-width)
      )
      .append("text")
      .attr("fill", "#000")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", "-7em")
      .attr("dx", -height / 2)
      .attr("text-anchor", "middle")
      .text("Number of Exams");;

      
    lines = [];
    groups = ["four_no_exams", "three_no_exams", "two_no_exams", "one_no_exams", "five_no_exams"];
    for (group of groups) {
      lines[group] = {};
      lines[group].line = g
      .append('g')
      .attr("class", function(d){ return ("five_no_exams" == group) ? 'selected' : '' })
      .append("path")
        .datum(data)
        .attr("d", d3.line()
          .x(function(d) { return x(d.year) })
          .y(function(d) { return height })
        )
        .attr("class", group)
        .attr("stroke", function(d){ return ("five_no_exams" == group) ? '#ffab00' : '#ccc' })
        .style("stroke-width", 4)
        .style("fill", "none")

        lines[group].line
        .transition()
        .delay(d => Math.random()*1000)
        .duration(1000)
        .attr("d", d3.line()
            .x(function(d) { return x(d.year) })
            .y(function(d) { return y(d[group]) })
          )
    }

    setInterval(() => {
        let newFirst = $('.ui.range.slider').slider('get thumbValue', 'first')
        let newSecond = $('.ui.range.slider').slider('get thumbValue', 'second')

        if(newFirst != first || newSecond != second) {
          var change = Math.abs(first - newFirst) + Math.abs(second - newSecond);
          first = newFirst;
          second = newSecond;

          let xDomain = data.map(d => d.year).filter(n => n >= first && n <= second);
          x.domain(xDomain)
          xAxis.transition().duration(1000).call(d3.axisBottom(x));

          var t = d3.transition()
            .duration(1500)
            .ease(d3.easeLinear);
          for (group of groups) {
            d3.selectAll(`.${group}`)
        .datum(data.filter(n => n.year >= first && n.year <= second))
        .transition(t)
        .attr("d", d3.line()
            .x(function(d) { return x(d.year) + (x.bandwidth()/2)})
            .y(function(d) { return y(d[group]) })
          )
          }
          
          
        }
      }, 500);

    function update(selectedGroup) {
      for(group of groups) {
      lines[group].line
          .transition()
          .duration(500)
          .attr("stroke", function(d){ return group == selectedGroup ? '#ffab00' : '#ccc' })
      }
    }

    d3.select("#selectButton").on("change", function(d) {
        var selectedOption = d3.select(this).property("value")
        update(selectedOption)
    })

    g.append("text")
      .attr("x", width / 2 )
      .attr("y", "-0.5em")
      .style("text-anchor", "middle")
      .text("Number of Exams By Score");
  }
</script>