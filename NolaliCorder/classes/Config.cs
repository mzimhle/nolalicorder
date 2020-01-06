using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;

namespace NolaliCorder
{
    // Class to just put in the json items that we will fetch. 
    // Class to just put in the json items that we will fetch. 
    public class Config
    {
        // This is the GUID for this application in this particular location.
        public string id { get; set; }
        // This is the API link where we will get future dates for playing the video
        public string url { get; set; }
        // Name of the store
        public string name { get; set; }
        // API version number
        public string api { get; set; }
        // Client ID as its on the database
        public string account { get; set; }
    }
}