import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';
import VentaBoleta from  './ventaBoleta';
import VentaFactura from './ventaFactura';
import NuevaVenta from './venta/nuevaVenta';
import EliminarVenta from './eliminarVenta'

class Venta extends Component {
    constructor(){
        super();
        this.state ={

            venta_tb                : [],
            addComponentFactura     : false,
            addComponentBoleta      : false,
            addComponentNuevaVenta  : false,
            dataFacturaReady        : false,
            dataBoletaReady         : false,
            dataNuevaVentaReady     : false,
            dataFactura             : {},
            dataBoleta              : {},
            dataNuevaVenta          : {},

            txt_btn_venta           : 'Nueva Venta',
            // DATOS A PASAR A UN COMPROBANTE ( VENTA, BOLETA, FACTURA, ETC.)
            tipoComprobante         : '',
            numeroVenta             : '',

            // BUSCADOR
            estadoBoton             : [],
            numeroPagina            : 1,
            inp_cliente             : '',
            inp_numeroVenta         : '',





        }
        this.addFactura           = this.addFactura.bind(this);
        this.addBoleta            = this.addBoleta.bind(this);
        this.addNuevaVenta        = this.addNuevaVenta.bind(this);
        this.fechtDataFactura     = this.fechtDataFactura.bind(this);
        this.fechtDataBoleta      = this.fechtDataBoleta.bind(this);
        this.fechtDataNuevaVenta  = this.fechtDataNuevaVenta.bind(this);
        this.fetchVentaDataTabla  = this.fetchVentaDataTabla.bind(this);

        this.getCambioNombreCliente = this.getCambioNombreCliente.bind(this);
        this.getCambioNumeroVenta   = this.getCambioNumeroVenta.bind(this);
    }




    fechtDataFactura(){
      fetch('/ventas/factura/serie')
      .then(res => res.json())
      .then(data => {
          this.setState({dataFactura: data},()=>{
            console.log(this.state.dataFactura)
            this.setState({dataFacturaReady: true},()=>console.log(this.state.dataFacturaReady))
                });
        })
    }
    
    fechtDataBoleta(){
        fetch('/ventas/boleta/serie')
        .then(res => res.json())
        .then(data => {
            this.setState({dataBoleta: data},()=>{
              console.log(this.state.dataBoleta);
              this.setState({dataBoletaReady: true},()=>console.log(this.state.dataBoletaReady))
                  });
          })
    }

    fechtDataNuevaVenta(){
        fetch('/ventas/venta/serie')
        .then(res => res.json())
        .then(data => {
            console.log("datos primeros:")
            console.log(data);
            this.setState({
                sucursalId      : data['sucursalId'],
                dataNuevaVenta  : data['tiposcomprobante']

            },()=>{
                console.log(this.state.dataNuevaVenta);
                this.setState({
                  dataNuevaVentaReady   : true,
                  tipoComprobante       : this.state.dataNuevaVenta['id'],
                  numeroVenta           : this.state.dataNuevaVenta['correlativo']
                })
            });
        })
    }

    componentDidMount(){
      this.fechtDataFactura();
      this.fechtDataBoleta();
      this.fechtDataNuevaVenta();
      this.fetchVentaDataTabla(1, '', '');
    }

    addFactura() {
      this.setState({
        addComponentBoleta: false,
        addComponentFactura : !this.state.addComponentFactura

      })
    }
    addBoleta() {
      this.setState({
        addComponentFactura : false,
        addComponentBoleta : !this.state.addComponentBoleta

      })
    }
    addNuevaVenta() {
        console.log(this.state.tipoComprobante)
        console.log(this.state.numeroVenta)
        console.log(this.state.sucursalId)
        let btn_venta = document.querySelector('#btn_venta');
        let txt_btn = '';
        if(this.state.addComponentNuevaVenta == true){
            btn_venta.classList.remove('btn-danger');
            btn_venta.classList.add('btn-success');
            txt_btn = "Nueva Venta";
        }else{
            btn_venta.classList.remove('btn-success');
            btn_venta.classList.add('btn-danger');
            txt_btn = "Cancelar Venta";
            
        }
        
        this.setState({
          addComponentFactura       : false,
          addComponentBoleta        : false,
          addComponentNuevaVenta    : !this.state.addComponentNuevaVenta,
          txt_btn_venta             : txt_btn 
  
        })
    }

    fetchVentaDataTabla(number, cliente, numeroComprobante){
        fetch(
            `/ventas/tb_ventas?page=${number}&bcliente=${cliente}&bnumeroComprobante=${numeroComprobante}`
        )
            .then(res => res.json())
            .then(data => {
                this.setState({
                    venta_tb        : data,
                    numeroPagina    : number
                },()=>{
                  this.agregarBotones()
                });

        })
    }

    getCambioNombreCliente(e){
        const {name , value} = e.target;
        this.setState({
            inp_cliente : value
        })
        this.fetchVentaDataTabla(this.state.numeroPagina, value, this.state.inp_numeroVenta)
    }

    getCambioNumeroVenta(e){
        const {name , value} = e.target;
        this.setState({
            inp_numeroVenta : value
        })
        this.fetchVentaDataTabla(this.state.numeroPagina, this.state.inp_cliente, value)
    }

    agregarBotones(){
        let z = this.state.venta_tb.last_page;
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

    render(){
      return(
            <div className="row">
                <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h6 className="card-title">Generar tipo de venta</h6>
                          {/* <button type="button" id= "btn_factura" onClick={()=>this.addFactura()}
                              className="btn btn-primary btn-rounded btn-fw desactivado">
                                  <span id="textFactura">FACTURA ELECTRÓNICA</span></button>
                          <button type="button" id= "btn_boleta" onClick={()=>this.addBoleta()}
                              className="btn btn-success btn-rounded btn-fw desactivado">
                                  <span id="textBoleta">BOLETA DE VENTA ELECTRÓNICA</span>
                            </button> */}

                            <button type="button" id= "btn_venta" onClick={()=>this.addNuevaVenta()} //miomio
                                className="btn btn-success btn-rounded btn-fw desactivado">
                                  <span id="textBoleta">{this.state.txt_btn_venta} </span>
                            </button>
                      </div>
                  </div>
               </div>

              { this.state.addComponentFactura     && this.state.dataFacturaReady    && <VentaFactura  dataFactura={this.state.dataFactura}  />}
              { this.state.addComponentBoleta      && this.state.dataBoletaReady     && <VentaBoleta   dataBoleta={this.state.dataBoleta} />}
              { this.state.addComponentNuevaVenta  && 
                <NuevaVenta 
                    tipoComprobante     = {this.state.tipoComprobante} 
                    numeroVenta         = {this.state.numeroVenta} 
                    sucursalId          = {this.state.sucursalId} 
                    addNuevaVenta       = {this.addNuevaVenta}
                    fetchVentaDataTabla = {this.fetchVentaDataTabla}
                    fechtDataNuevaVenta = {this.fechtDataNuevaVenta}
                />
              }


              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Buscar</h4>
                          {/* @csrf */}
                          <div className="row">
                              <div className="col-3">
                                  <label>Cliente</label>
                                  <input 
                                    type        = "text" 
                                    className   = "form-control form-control-lg" 
                                    name        = "buscar_tb_cliente" 
                                    id          = "buscar_tb_cliente"
                                    value       = {this.state.inp_cliente}
                                    onChange = {this.getCambioNombreCliente}
                                    />
                              </div>
                              {/* <div className="col-3">
                                  <label>Tipo de comprobante</label>
                                  <select className="form-control" name ="buscar_tb_comprobante" id="buscar_tb_comprobante">
                                      <option value="">SELECCIONA UN COMPROBANTE</option>
                                      <option value="BOLETA">BOLETA</option>
                                      <option value="FACTURA">FACTURA</option>
                                  </select>
                              </div> */}
                              <div className="col-3">
                                  <label>Numero de comprobante</label>
                                  <input 
                                    type        = "text" 
                                    className   = "form-control form-control-lg" 
                                    name        = "buscar_tb_numeroComprobante" 
                                    onChange    = {this.getCambioNumeroVenta}
                                    value       = {this.state.inp_numeroVenta}
                                    id="buscar_tb_numeroComprobante"/>
                              </div>
                              <div className="col-3">
                                  <label>Filtro por Fechas</label>
                                  <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                    name="buscar_tb_fecnumeroComprobante"
                                    id="buscar_tb_fecnumeroComprobante" defaultValue=''/>
                              </div>

                          </div>
                      </div>
                  </div>
              </div>
              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h6 className="card-title">Comprobantes</h6>
                          <table id="tb_ventas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Fecha Emisión</th>
                                      <th>Cliente</th>
                                      <th>Tipo Comprobante</th>
                                      <th>Número</th>
                                      <th>Estado</th>
                                      <th>SubTotal</th>
                                      <th>Total</th>
                                      <th colSpan="2">Acciones</th>
                                  </tr>
                                      {
                                        this.state.venta_tb.data 
                                        ?this.state.venta_tb.data.map((task, posicion) =>{
                                            return (
                                                <tr key={task.idVentas}>
                                                    <td>{posicion}</td>
                                                    <td>{task.fechaVentas}</td>
                                                    <td>{task.nombreClientes}</td>
                                                    <td>{task.nombreTiposcomprobante}</td>
                                                    <td>{task.numeroVentas}</td>
                                                    <td>{task.estadoSunatVentas}</td>
                                                    <td>{task.subTotalVentas}</td>
                                                    <td>{task.totalVentas}</td>

                                                    <td>
                                                        {
                                                            task.estadoSunatVentas == 2
                                                            ?<button 
                                                                type="button" 
                                                                className="btn waves-effect waves-light btn-rounded btn-danger">
                                                                    VENTA CANCELADA
                                                            </button>

                                                            :<EliminarVenta 
                                                                key                 = {task.idVentas} 
                                                                idVenta             = {task.idVentas}
                                                                fetchVentaDataTabla = {this.fetchVentaDataTabla}
                                                            />
                                                        }
                                                        
                                                        <a 
                                                            href={'/ventas/pdf/'+task.idVentas} 
                                                            target="_blank"
                                                            className="btn btn-rounded btn-fw btn-primary ver" 
                                                            type="button" >
                                                                <i className="mdi mdi-eye"></i>
                                                        </a>
                                                        

                                                        
                                                    </td>
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
                                                    onClick={()=>this.fetchVentaDataTabla(task, this.state.inp_cliente, this.state.inp_numeroVenta)}>{task}</button>

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
}}

export default Venta
