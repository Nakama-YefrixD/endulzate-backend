import React from 'react'
import {Component} from 'react';
//import { VictoryBar, VictoryChart, VictoryAxis } from 'victory';

  class Graficos extends Component {

      constructor(){
          super();
          this.state ={

            data:[
                {quarter: 1, earnings: 130},
                {quarter: 2, earnings: 160},
                {quarter: 3, earnings: 140},
                {quarter: 4, earnings: 190}
                ]
          }
        }




  render(){
      return(

        <div className="col-lg-12 grid-margin stretch-card">
            <div className="card">
                <div className="card-body">
                   <div class="row justify-content-md-center">
                    <div className= "col-6" >
                            {/*  <VictoryChart
                                  // domainPadding will add space to each side of VictoryBar to
                                  // prevent it from overlapping the axis
                                  domainPadding={20}
                                >
                                  <VictoryAxis
                                    // tickValues specifies both the number of ticks and where
                                    // they are placed on the axis
                                    tickValues={[1, 2, 3, 4]}
                                    tickFormat={["Quarter 1", "Quarter 2", "Quarter 3", "Quarter 4"]}
                                  />
                                  <VictoryAxis
                                    dependentAxis
                                    // tickFormat specifies how ticks should be displayed
                                    tickFormat={(x) => (`$${x / 10}k`)}
                                  />
                                  <VictoryBar
                                    data={this.state.data}
                                    x="quarter"
                                    y="earnings"
                                  />
                                </VictoryChart> */}
                    </div>
                    </div>
                </div>
            </div>
        </div>
      )
    }
}

export default Graficos
