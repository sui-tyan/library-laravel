import ApexCharts from "apexcharts";

var options = {
    series: [
        {
            name: "SHS",
            data: [22, 34, 8, 15, 41, 39, 27, 46, 51, 13, 48, 7],
        },
        {
            name: "CAS",
            data: [44, 27, 9, 21, 36, 15, 6, 30, 50, 14, 5, 37],
        },
        {
            name: "CEA",
            data: [13, 45, 8, 40, 51, 22, 27, 33, 50, 2, 31, 9],
        },
        {
            name: "CMA",
            data: [38, 47, 17, 24, 20, 35, 10, 50, 23, 4, 7, 32],
        },
        {
            name: "CELA",
            data: [30, 19, 2, 16, 36, 7, 41, 42, 12, 32, 14, 51],
        },
        {
            name: "CHS",
            data: [24, 20, 3, 45, 50, 28, 8, 36, 2, 51, 15, 12],
        },
        {
            name: "CITE",
            data: [7, 26, 45, 35, 14, 29, 39, 21, 41, 48, 2, 16],
        },
        {
            name: "CCJE",
            data: [23, 30, 51, 8, 37, 44, 11, 50, 3, 24, 7, 38],
        },
    ],
    chart: {
        height: 450,
        type: "bar",
        dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
        },
        toolbar: {
            show: true,
            offsetY: -20,
            tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true,
            },
        },
        zoom: {
            enabled: true,
        },
    },
    colors: [
        "#48523e",
        "#49672f",
        "#690404",
        "#c5b32a",
        "#201cef",
        "#989899",
        "#000000",
        "#81a581",
    ],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Per Department Graph",
        align: "left",
        offsetY: 10,
    },
    grid: {
        borderColor: "#e7e7e7",
        row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
        },
        padding: {
            left: 30, // or whatever value that works
            right: 30, // or whatever value that works
        },
    },
    markers: {
        size: 1,
    },
    xaxis: {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
        tickPlacement: "on",
        title: {
            text: "Month",
        },
        zoom: {
            enabled: true,
            type: "x",
            autoScaleYaxis: false,
            zoomedArea: {
                fill: {
                    color: "#90CAF9",
                    opacity: 0.4,
                },
                stroke: {
                    color: "#0D47A1",
                    opacity: 0.4,
                    width: 1,
                },
            },
        },
        min: 3,
        max: 3,
    },
    yaxis: {
        title: {
            text: "Number of Students",
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },
};

var departmentGraphChart = new ApexCharts(
    document.querySelector("#departmentGraph"),
    options
);
departmentGraphChart.render();
