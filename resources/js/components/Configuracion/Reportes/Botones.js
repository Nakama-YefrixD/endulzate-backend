import React from 'react'
import {Component} from 'react';

  class Botones extends Component {

      constructor(){
          super();
          this.state ={
          }
        }

render(){
    return(
        <div className="col-2">

            {/*<button className="btn waves-effect waves-light btn-warning" onClick={()=>this.props.generateGraficos()} >Mostrar Grafico</button>*/}
            <button className="btn waves-effect waves-light btn-success" onClick={()=>this.props.generateExcel()} >Generar excel</button>

        </div>
    )
  }
}

export default Botones
