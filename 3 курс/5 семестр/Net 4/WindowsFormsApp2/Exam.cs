﻿using System;
using System.Drawing;

namespace WindowsFormsApp2
{
    [Serializable]
    public class Exam
    {
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public DateTime Date { get; set; }
        public int Mark { get; set; }
        public string Questions { get; set; }
        public string Photo { get; set; }

        public Exam(string FirstName, string LastName, DateTime Date, int Mark, string Questions, string Photo)
        {
            this.FirstName = FirstName;
            this.LastName = LastName;
            this.Date = Date;
            this.Mark = Mark;
            this.Questions = Questions;
            this.Photo = Photo;
        }
    }
}