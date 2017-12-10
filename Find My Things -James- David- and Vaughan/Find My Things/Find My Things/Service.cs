using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Find_My_Things
{
    class Service
    {
        public string Location { get; set; }
        public string Name { get; set; }        
        public string ResourceType { get; set; }

        public string Display()
        {          
            return Name[0].ToString().ToUpper() + Name.Substring(1, Name.Length-1);
        }

        public override string ToString()
        {
            return Name;
        }
    }
}