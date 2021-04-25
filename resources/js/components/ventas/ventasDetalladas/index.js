import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';


class VentasDetalladas extends Component {
    constructor(){
        super();
        this.state ={
            tb_venta_detallada : [],

            estadoBoton        : [],
            numeroPagina       : 1,
            bcodProduct        : '',
            bnumeroComprobante : '',
            bdateOne           : '',
            bdateTwo           : '',
            

        }

        this.fetchTbVentaDetallada  = this.fetchTbVentaDetallada.bind(this);
        this.getCambioCodProducto   = this.getCambioCodProducto.bind(this);
        this.getCambioNumeroVenta   = this.getCambioNumeroVenta.bind(this);
        this.getCambioDateOne       = this.getCambioDateOne.bind(this);
        this.getCambioDateTwo       = this.getCambioDateTwo.bind(this);

    }

    fetchTbVentaDetallada(numeroPag, codProduct, numeroComprobante, dateOne, dateTwo){
        fetch(
            `/ventas/tb_ventas_detalladas?page=${numeroPag}&bcodProduct=${codProduct}&bnumeroComprobante=${numeroComprobante}&dateOne=${dateOne}&dateTwo=${dateTwo}`
        )
            .then(
                res => res.json(),
                
            )
            .then(data => {
                this.state.tb_venta_detallada = data
                this.setState({
                    tb_venta_detallada  : this.state.tb_venta_detallada,
                    numeroPagina        : numeroPag
                },()=>{
                  this.agregarBotones()
                });

        })
    }


    componentDidMount(){
        this.fetchTbVentaDetallada(1, '', '', '', '');
    }
    
    agregarBotones(){
        let z = this.state.tb_venta_detallada.last_page;
        let x = [];
        if(z){
            for(let i = 1 ; i <= z ; i++){
                x.push(i);
            }
            this.setState({
                estadoBoton: x
            })
        }
    }

    getCambioCodProducto(e){
        const {name , value} = e.target;
        this.setState({
            bcodProduct : value
        })
        this.fetchTbVentaDetallada(
            this.state.numeroPagina, 
            value, 
            this.state.bnumeroComprobante,
            this.state.bdateOne,
            this.state.bdateTwo,

        )
    }

    getCambioNumeroVenta(e){
        const {name , value} = e.target;
        this.setState({
            bnumeroComprobante : value
        })
        this.fetchTbVentaDetallada(
            this.state.numeroPagina, 
            this.state.bcodProduct, 
            value,
            this.state.bdateOne,
            this.state.bdateTwo,

        )
    }

    getCambioDateOne(e){
        const {name , value} = e.target;
        this.setState({
            bdateOne : value
        })
        this.fetchTbVentaDetallada(
            this.state.numeroPagina, 
            this.state.bcodProduct, 
            this.state.bnumeroComprobante,
            value,
            this.state.bdateTwo,

        )
    }

    getCambioDateTwo(e){
        const {name , value} = e.target;
        this.setState({
            bdateTwo : value
        })
        this.fetchTbVentaDetallada(
            this.state.numeroPagina, 
            this.state.bcodProduct, 
            this.state.bnumeroComprobante,
            this.state.bdateOne,
            value,

        )
    }

    render(){
        return(
            <div className="row">
                <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Buscar</h4>
                          {/* @csrf */}
                          <div className="row">
                              <div className="col-3">
                                  <label>Codigo Producto</label>
                                  <input 
                                    type        = "text" 
                                    className   = "form-control form-control-lg" 
                                    value       = {this.state.bcodProduct}
                                    onChange    = {this.getCambioCodProducto}
                                    />
                              </div>           
                              <div className="col-3">
                                  <label>Numero de comprobante</label>
                                  <input 
                                    type        = "text" 
                                    className   = "form-control form-control-lg" 
                                    value       = {this.state.bnumeroComprobante}
                                    onChange    = {this.getCambioNumeroVenta}
                                    />
                              </div>
                              <div className="col-3">
                                  <label>Desde (AAAA-MM-dd)</label>
                                  <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                    value        = {this.state.bdateOne}
                                    onChange     = {this.getCambioDateOne}
                                    />
                              </div>
                              <div className="col-3">
                                  <label>Hasta (AAAA-MM-dd)</label>
                                  <input 
                                    type         =  "text" 
                                    className    =  "form-control form-control-lg" 
                                    value        = {this.state.bdateTwo}
                                    onChange     = {this.getCambioDateTwo}
                                    />
                              </div>

                          </div>
                      </div>
                  </div>
              </div>
              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h6 className="card-title">Venta Detallada</h6>
                          <table id="tb_ventas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>Sucursal</th>
                                      <th>Fecha Emisión</th>
                                      <th>Tipo Comprobante</th>
                                      <th>N° Venta</th>
                                      <th>Codigo Producto</th>
                                      <th>Nombre Producto</th>
                                      <th>Precio</th>
                                      <th>Cantidad</th>
                                      <th>Descuento</th>
                                      <th>SubTotal</th>
                                      <th>Total</th>
                                  </tr>
                                      {
                                        this.state.tb_venta_detallada.data 
                                        ?this.state.tb_venta_detallada.data.map((task, posicion) =>{
                                            return (
                                                <tr key={task.idDetalleVenta}>
                                                    <td>{task.nombreSucursal}</td>
                                                    <td>{task.fechaVentas}</td>
                                                    <td>{task.nombreTiposcomprobante}</td>
                                                    <td>{task.numeroVentas}</td>
                                                    <td>{task.codigoProducto}</td>
                                                    <td>{task.nombreProducto}</td>
                                                    <td>{task.precioProducto}</td>
                                                    <td>{task.cantidadDetalleVenta}</td>
                                                    <td>{task.descuentoDetalleVenta}</td>
                                                    <td>{task.subtotalDetalleVenta}</td>
                                                    <td>{task.totalDetalleVenta}</td>

                                                    
                                                </tr>
                                            );
                                        })
                                        : null
                                      }

                              </thead>
                          </table>
                          <div className="container">
                            <div className="row justify-content-end">
                                <div className="col-4">
                                    {
                                        this.state.estadoBoton
                                        ?this.state.estadoBoton.map(task =>{
                                            return (
                                                <button key={task}
                                                    className="btn btn-sm btn-secondary editar" 
                                                    type="button" 
                                                    onClick={()=>this.fetchTbVentaDetallada(
                                                        task, 
                                                        this.state.bcodProduct, 
                                                        this.state.bnumeroComprobante,
                                                        this.state.bdateOne,
                                                        this.state.bdateTwo
                                                        )}>{task}</button>

                                            );
                                        })   
                                        : null
                                    }
                                </div>
                            </div>
                         </div>
                      </div>
                  </div>
              </div>

            </div>

        )
    }
}

export default VentasDetalladas
