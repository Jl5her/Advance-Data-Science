// const margin = {
//   top: 20,
//   right: 75,
//   bottom: 30,
//   left: 75
// }

function makeBarChart(data, xVar, yVar, id) {
  if(document.getElementById(id) === null) return;
  const margin = {
    top: 20,
    right: 75,
    bottom: 30,
    left: 75
  }
  const svg = d3.select(`svg#${id}`),
  width = +svg.attr("width") - margin.left - margin.right,
  height = +svg.attr("height") - margin.top - margin.bottom,
  g = svg.append("g").attr("transform", `translate(${margin.left}, ${margin.top})`);

  const tooltip = d3.select('body').append("div").attr("class", "tooltip").style("opacity", 0);

  const x = d3.scaleBand().rangeRound([0, width]).padding(0.1);
  const y = d3.scaleLinear().rangeRound([height, 0]);

  x.domain(data.map((d) => d[xVar]))
  y.domain([0, d3.max(data, (d) => d[yVar])])

  g.append("g")
    .attr("transform", `translate(0, ${height})`)
    .call(d3.axisBottom(x))

  g.append("g")
    .call(d3.axisLeft(y)
      .tickSize(-width)
    )
    .append("text")
    .attr("fill", "#000")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "0.71em")
    .attr("dx", "-0.71em")
    .attr("text-anchor", "end")
    .text("Number of Exams");

  g.selectAll(".bar")
    .data(data)
    .enter().append("rect")
    .attr('class', 'bar')
    .attr("x", (d) => x(d[xVar]))
    .attr("y", (d) => y(d[yVar]))
    .attr("width", x.bandwidth())
    .attr("height", (d) => height - y(d[yVar]))
    .on("mousemove", (d) => {
      const svgPos = svg.node().getBoundingClientRect();

      const yPos = svgPos.y + y(d[yVar]) - 10;
      const xPos =  svgPos.x + x(d[xVar]) + (x.bandwidth() / 4);

      tooltip.transition()
        .duration(200)
        .style("opacity", 1);
      tooltip.html(
        `<b>${xVar}:</b> ${d[xVar]}<br/> \
         <b>${yVar}:</b> ${d[yVar].toLocaleString()}<br/> \
         <b>avg:</b> ${d.avg.toFixed(2)}`)
        .style("left", `${xPos}px`)
        .style("top", `${yPos}px`);
    })
    .on("mouseout", (d) => {
      tooltip.transition()
        .duration(500)
        .style("opacity", 0);
    });

    // // Line

    const yScale = d3.scaleLinear().rangeRound([height, 0]);
    yScale.domain([0, 5])

    var line = d3.line()
    .x(function(d, i) { return x(d.year) + (x.bandwidth() / 2) }) // set the x values for the line generator
    .y(function(d) { return yScale(d.avg); }) // set the y values for the line generator 
    .curve(d3.curveMonotoneX) // apply smoothing to the line

    g.append("path")
    .datum(data) 
    .attr("class", "line") 
    .attr("d", line);

    g.append("g")
        .attr("class", "y1 axis")
        .attr("transform", `translate(${width}, 0)`)
        .call(d3.axisRight(yScale)); // Create an axis component with d3.axisLeft

    svg.append("text")
    .attr("x", width / 2 )
    .attr("y", 10)
    .style("text-anchor", "middle")
    .text("Number of Exams Taken By Year");
}

function makeLineChart(data, id) {
  
}

function makeDistroGraph(data, id) {
  const svg = d3.select(`svg#${id}`),
  width = +svg.attr("width") - margin.left - margin.right,
  height = +svg.attr("height") - margin.top - margin.bottom,
  g = svg.append("g").attr("transform", `translate(${margin.left}, ${margin.top})`);

  var years = data.map(function(d) { return d.year; });
  var scoreNames       = data[0].exams.map(function(d) { return d.category; });
 
  const x0  = d3.scaleBand().rangeRound([0, width], .5);
  const x1  = d3.scaleBand();
  const y   = d3.scaleLinear().rangeRound([height, 0]);

  x0.domain(years);
  x1.domain(scoreNames).rangeRound([0, x0.bandwidth()]);
  y.domain([0, d3.max(data, year => d3.max(year.exams, exam => exam.value))]);

  const color = d3.scaleOrdinal(d3.schemeCategory10);

  g.append("g")
    .attr("transform", `translate(0,${height})`)
    .call(d3.axisBottom().scale(x0).tickValues(data.map(d => d.year)));

  g.append("g")
    .attr("class", "y axis")
    .style('opacity','0')
    .call(d3.axisLeft(y)
    .tickSize(-width))
    .append("text")
    .attr("fill", "#000")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "0.71em")
    .attr("dx", "-0.71em")
    .attr("text-anchor", "end")
    .text("Number of Exams");

  g.select('.y').transition().duration(500).delay(1300).style('opacity','1');

  var slice = g.selectAll(".slice")
    .data(data)
    .enter().append("g")
    .attr("class", "g")
    .attr("transform", d => `translate(${x0(d.year)},0)`);

  slice.selectAll("rect")
  .data(d => d.exams)
    .enter().append("rect")
        .attr("width", x1.bandwidth())
        .attr("x", d => x1(d.category))
          .style("fill", d => color(d.category))
          .attr("y", _ => y(0))
          .attr("height", _ => height - y(0) )
        .on("mouseover", function(d) {
          d3.select(this).style("fill", d3.rgb(color(d.category)).darker(2))
        })
        .on("mouseout", function(d) {
          d3.select(this).style("fill", color(d.category))
        });

  slice.selectAll("rect")
    .transition()
    .delay(d => Math.random()*1000)
    .duration(1000)
    .attr("y", d => y(d.value))
    .attr("height", d => height - y(d.value));

  svg.append("text")
    .attr("x", width / 2 )
    .attr("y", 10)
    .style("text-anchor", "middle")
    .text("Number of Exams Taken By Year");

    // Legend
  var legend = svg.selectAll(".legend")
      .data(data[0].exams.map(function(d) { return d.category; }).reverse())
      .enter().append("g")
        .attr("class", "legend")
        .attr("transform", function(d,i) { return "translate(0," + i * 20 + ")"; })
        .style("opacity","0");

  legend.append("rect")
      .attr("x", 18 + margin.left)
      .attr("y", 9 + margin.top)
      .attr("width", 18)
      .attr("height", 18)
      .style("fill", function(d) { return color(d); });

  legend.append("text")
      .attr("x", 40 + margin.left)
      .attr("y",  margin.top)
      .attr("dy", 0.75 + margin.top + "px")
      // .style("text-anchor", "end")
      .text(function(d) {return d; });

  legend.transition().duration(500).delay(function(d,i){ return 1300 + 100 * i; }).style("opacity","1");

  function update(selectedGroup) {

    // Create new data with the selection?
    var dataFilter = data.map(function(d){return {time: d.time, value:d[selectedGroup]} })

    // Give these new data to update line
    line
        .datum(dataFilter)
        .transition()
        .duration(1000)
        .attr("d", d3.line()
          .x(function(d) { return x(+d.time) })
          .y(function(d) { return y(+d.value) })
        )
        .attr("stroke", function(d){ return myColor(selectedGroup) })
  }

  d3.select("#selectButton").on("change", function(d) {
    // recover the option that has been chosen
    var selectedOption = d3.select(this).property("value")
    // run the updateChart function with this selected option
    update(selectedOption)
  })
}