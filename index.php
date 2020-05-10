<title>Number of Exams Taken By Year</title>

<meta id="page" value="exams"></meta>

<?php require_once("include/header.php"); ?>

  
<h4>Filter Year</h4>
<div class="ui labeled ticked range slider" id="slider-range"></div>
    <svg id="count" width="1000" height="500"></svg>
<?php require_once("include/footer.php"); ?>



<script>
let first, second;

  function makeScoresGraph(data) {
  const margin = {
    top: 20,
    right: 75,
    bottom: 75,
    left: 75
  }
    const svg = d3.select(`svg`),
    width = +svg.attr("width") - margin.left - margin.right,
    height = +svg.attr("height") - margin.top - margin.bottom,
    g = svg.append("g").attr("transform", `translate(${margin.left}, ${margin.top})`);

    const tooltip = d3.select('body').append("div").attr("class", "tooltip").style("opacity", 0);

    first = d3.min(data, d => d.year);
    second = d3.max(data, d => d.year);

    $('.ui.range.slider')
      .slider({
        min: first,
        max: second,
        start: first,
        end: second,
      })

    const x = d3.scaleBand()
      .domain(data.map((d) => d.year))
      .rangeRound([0, width])
      .padding(0.1);

    const y = d3.scaleLinear()
      .domain([0, d3.max(data, (d) => d.total)])
      .rangeRound([height, 0]);

    const xAxis = g.append("g")
      .attr("transform", `translate(0, ${height})`)
      .call(d3.axisBottom(x))

    xAxis.append("text")
      .attr("fill", "#000")
      .attr("y", 6)
      .attr("dy", "3em")
      .attr("dx",  width / 2)
      .attr("text-anchor", "middle")
      .text("Year");

    g.append("g")
      .call(d3.axisLeft(y)
        .tickSize(-width)
      )
      .append("text")
      .attr("class", "y axis")
      .style('opacity','0')
      .attr("fill", "#000")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", "-7em")
      .attr("dx", -height / 2)
      .attr("text-anchor", "middle")
      .text("Number of Exams");

    g.selectAll(".bar")
      .data(data)
      .enter().append("rect")
      .attr('class', 'bar')
      .attr("x", (d) => x(d.year))
      .attr("y", (d) => height)
      .attr("height", (d) => 0)
      .attr("width", x.bandwidth())
      .on("mousemove", function (d) {
        const pos = this.getBoundingClientRect();

        tooltip.transition()
          .duration(200)
          .style("opacity", 1);
        tooltip.html(
          `<b>year:</b> ${d.year}<br/> \
          <b>total:</b> ${d.total.toLocaleString()}<br/> \
          <b>avg:</b> ${d.avg.toFixed(2)}`)
          .style("left", `${pos.x + (x.bandwidth() / 2)}px`)
          .style("top", `${pos.y - 20}px`);
      })
      .on("mouseout", (d) => {
        tooltip.transition()
          .duration(500)
          .style("opacity", 0);
      })
      .transition()
      .delay(d => Math.random()*1000)
      .duration(1000)
      .attr("y", (d) => y(d.total))
      .attr("height", (d) => height - y(d.total));

      g.append("text")
      .attr("x", width / 2 )
      .attr("y", "-0.5em")
      .style("text-anchor", "middle")
      .text("Number of Exams Taken By Year");


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

          g.selectAll("rect")
            .data(data)
            .transition()
            .duration(1000 / change)
            .attr("x", function(d) { 
              if (x(d.year) == undefined) {
                $(this).animate({opacity: 0, x: -margin.left}, 1000);
              } else
                $(this).animate({x: x(d.year), opacity: 1}, 1000);
              return x(d.year)
            })
            .attr("width", x.bandwidth())
            .attr("y", (d) => { return y(d.total)})
            .attr("height", (d) => height - y(d.total));
        }
      }, 500);
    }

  </script>