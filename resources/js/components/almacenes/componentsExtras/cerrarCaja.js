import React from 'react'
import {Component} from 'react';
import Modal from 'react-bootstrap/Modal'


class CerrarCaja extends Component {

    constructor(){
        super();
        this.state ={}
    }


 render (){
   return(

     <Modal
        size='lg'
        show={this.props.estadoModalCerrarCaja}
        onHide={()=> this.props.cambiarModalCerrarCaja()}

       >
           <Modal.Header closeButton>
             <Modal.Title id="example-custom-modal-styling-title">
               Cerrar Caja
             </Modal.Title>
           </Modal.Header>
           <Modal.Body>
             <div className="card card-default">
                  <div className="modal-body">
                      <div className="card-body">
                              <div className="form-group">
                                  <div className="row">
                                      <div className="col-12">
                                          <label>Codigo de caja</label>
                                          <div className="input-group">
                                              <input type="text" className="form-control" name="codigoCerrarCaja" onChange={this.props.handleChange}  value={this.props.codigoCerrarCaja} onKeyDown={this.props.handleKeyDown5}/>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div className="form-group">
                                  <div className="row">
                                      <div className="col-4">
                                          <label>Nombre de Caja</label>
                                          <h4 className="card-title"> {this.props.nombreCajaCerrarCaja} </h4>
                                      </div>
                                      <div className="col-2">
                                          <label>Stock</label>
                                          <h4 className="card-title"> {this.props.stockCajaCerrarCaja} </h4>
                                      </div>
                                      <div className="col-4">
                                          <label>Cantidad de productos dentro</label>
                                          <h4 className="card-title"> {this.props.cantidadProductosCerrarCaja} </h4>
                                      </div>
                                      <div className="col-2">
                                          <label>Precio</label>
                                          <h4 className="card-title"> S/{this.props.precioCajaCerrarCaja} </h4>
                                      </div>
                                  </div>
                              </div>
                              <div className="form-group">
                                  <div className="row">
                                      <div className="col-6">
                                          <label>Nombre del producto</label>
                                          <h4 className="card-title"> {this.props.nombreProductoCerrarCaja} </h4>
                                      </div>
                                      <div className="col-4">
                                          <label>Stock</label>
                                          <h4 className="card-title"> {this.props.stockProductoCerrarCaja} </h4>
                                      </div>
                                      <div className="col-2">
                                          <label>Precio</label>
                                          <h4 className="card-title"> S/{this.props.precioProductoCerrarCaja} </h4>
                                      </div>
                                  </div>
                              </div>


                              <div className="form-group">
                                  <div className="row">
                                      <div className="col-12">
                                          <label>Cuantas cajas desea cesar</label>
                                          <div className="input-group">
                                              <input type="text" className="form-control" name="cantidadCerrarCaja" onChange={this.props.handleChange}  value={this.props.cantidadCerrarCaja}/>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div className="form-group boton">
                                  <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" onClick={()=>this.props.sendCerrarCaja()}>
                                      Cerrar Caja</button>
                              </div>
                         </div>
                  </div>
              </div>

           </Modal.Body>
       </Modal>
   )
 }}


export default CerrarCaja
