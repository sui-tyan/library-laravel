import "flowbite";

import ApexCharts from "apexcharts";
import booksBorrowers from "./booksBorrowers";
import departmentGraph from "./departmentGraph";

var booksBorrowersChart = new ApexCharts(
    document.querySelector("#booksBorrowers"),
    booksBorrowers
);
booksBorrowersChart.render();

var departmentGraphChart = new ApexCharts(
    document.querySelector("#departmentGraph"),
    departmentGraph
);
departmentGraphChart.render();
