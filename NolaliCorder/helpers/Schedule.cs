using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;


namespace NolaliCorder
{
    public class Schedule
    {
        /* Tag setting. */
        private static string TAG = typeof(Schedule).Name;
        /* Setup the timer
        private Timer timer;
        private TimerCallback timerCallback; */
        // This Enum defines the current state of the process.
        public enum State { Recording, Idle }
        // The "State" datatype is derived from the above "enum" of the state, which is defined having 3 states
        // Those states are Downloading, Done and idle, "idle" is the initial state when program starts.
        public static State RecordingState = State.Idle;

        public static async Task CheckToRecord()
        {
            try
            {
                // Check state of the system first
                if (RecordingState == State.Idle)
                {
                    if (WebService.timeTableObject.result)
                    {
                        // Before we play the video, lets check if the time is right.
                        foreach (var schedule in WebService.timeTableObject.schedule)
                        {
                            // Lets define our times
                            TimeSpan start = TimeSpan.Parse(schedule.start);
                            TimeSpan end = TimeSpan.Parse(schedule.end);
                            TimeSpan now = DateTime.Now.TimeOfDay;

                            WebService.timeTableObject.code = schedule.code;
                            // Check if we have something scheduled now.
                            if (now >= start && now <= end)
                            {
                                // Check if its recording. 
                                if (WebCam.Record())
                                {
                                    RecordingState = State.Recording;
                                    // Assign new record to object.                                        
                                    activeSchedule.day = WebService.timeTableObject.day;
                                    activeSchedule.code = schedule.code;
                                    activeSchedule.start = start;
                                    activeSchedule.end = end;
                                    // Display.
                                    Log.Notify(TAG, "Schedule Time Start: " + activeSchedule.start);
                                    Log.Notify(TAG, "Schedule Time End: " + activeSchedule.end);
                                    // Display the video currently playing as well as other information.
                                    Settings.PopulateInfo("Started recording: Starting: " + activeSchedule.start + " till " + activeSchedule.end);
                                    break;
                                }
                                else
                                {
                                    break;
                                }
                            }
                        }
                    }
                }
                else
                {
                    CalculateTime(activeSchedule.end);
                    // The video is currently being recorded, so now we are going to simply keep checking if time is up.
                    if (DateTime.Now.TimeOfDay > activeSchedule.end)
                    {
                        // Start recording.
                        bool stop = WebCam.Stop();
                        // Check if its recording. 
                        if (stop)
                        {
                            // The end time has passed, lets stop the video.
                            RecordingState = State.Idle;
                            activeSchedule.code = "";
                            activeSchedule.day = "";
                            Settings.PopulateInfo("Stopped recording: Starting: " + activeSchedule.start + " till " + activeSchedule.end);
                        }
                        else
                        {
                            // The end time has passed, lets stop the video.
                            Log.Notify(TAG, "Could not stop recording.....");
                        }
                    }
                }
            }
            catch (Exception e)
            {
                Log.Error(TAG, "Schedule.checkToRecord Message : " + e.Message);
                Log.Error(TAG, "Schedule.checkToRecord Tracert : " + e.StackTrace);

            }
        }

        public static void CalculateTime(TimeSpan end) {
            // Get the current time
            TimeSpan now = DateTime.Now.TimeOfDay;
            // Calculate difference
            TimeSpan diff = end - now;
            // Display it on the TextView.
            Settings.info.Text = "Time Left is - " + diff.Hours + " : " + diff.Minutes + " : " + diff.Seconds;
        }
    }
}