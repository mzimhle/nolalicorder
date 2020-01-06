using System;
using System.Collections.Generic;

namespace NolaliCorder
{
    public class TimeTable
    {
        public string code { get; set; }
        public string day { get; set; }
        public List<Times> schedule { get; set; }
        public string message { get; set; }
        public bool result { get; set; }
    }

    public class Times
    {
        public string code { get; set; }
        public string start { get; set; }
        public string end { get; set; }
    }
}