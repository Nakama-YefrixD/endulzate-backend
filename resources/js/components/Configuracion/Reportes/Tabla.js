import React from 'react'
import {Component} from 'react';

  class Tabla extends Component {

      constructor(){
          super();
          this.state ={
          }
        }

render(){
    return(
          <div className="col-lg-12 grid-margin stretch-card">
              <div className="card">
                  <div className="card-body">
                      <h4 className="card-title">Detalles (seguimiento en el Tiempo)</h4>
                      <table id="tb_usuarios" className="table table-striped" style={{width:'100%'}}>
                          <thead>
                              <tr>
                                  <th>SUCURSAL</th>
                                  <th>FECHA</th>
                                  <th>ACCION</th>
                                  <th>NUMERO</th>
                                  <th>CANTIDAD</th>
                                  <th>STOCK</th>
                              </tr>
                          </thead>
                          <tbody>
                               {
                                  this.props.reporte.movimientos ?
                                  this.props.reporte.movimientos.map((task ,index) =>{
                                      if (task.operacion == 1 ){
                                      return (

                                                <tr className="table-success" key={index}>
                                                    <td>{task.sucursal}</td>
                                                    <td>{task.fecha}</td>
                                                    <td>{task.accion}</td>
                                                    <td>{task.registro}</td>
                                                    <td>+{task.cantidad}</td>
                                                    <td>{task.stock}</td>
                                                </tr>

                                      )}
                                      else {
                                        return(
                                            <tr className="table-danger" key={index}>
                                                <td>{task.sucursal}</td>
                                                <td>{task.fecha}</td>
                                                <td>{task.accion}</td>
                                                <td>{task.registro}</td>
                                                <td>-{task.cantidad}</td>
                                                <td>{task.stock}</td>
                                            </tr>
                                      )}
                                   } )   : null
                                  }
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
    )
  }
}

export default Tabla
