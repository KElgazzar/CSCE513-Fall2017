using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Find_My_Things.DataModels
{
    class Light
    {
        public string rt { get; set; }
        public string ct { get; set; }
        public string title { get; set; }
        public string Path { get; set; }

        public string Serialize()
        {
            return JsonConvert.SerializeObject(this);
        }

        public static Light Deserialize(string json)
        {
            return JsonConvert.DeserializeObject<Light>(json);
        }


    }
}
