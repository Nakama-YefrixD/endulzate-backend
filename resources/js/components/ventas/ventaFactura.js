import React from 'react'
import {Component} from 'react';
import Modal from 'react-bootstrap/Modal'

class VentaFactura extends Component {
    constructor(){
        super();
        this.state ={
            data:'hola',
            estadoa:false,
            estadob:false
            
            
        }
        this.cambiarb= this.cambiarb.bind(this);
        this.cambiara= this.cambiara.bind(this);
        this.cambio= this.cambio.bind(this);   
}
    cambiara() {
        this.setState({
        estadoa: !this.state.estadoa
        });
    }
    cambiarb(){
        this.setState({
            estadob: !this.state.estadob
        })
    }   

    cambio() {
        this.setState({
        estadoa: !this.state.estadoa,
        estadob: !this.state.estadob
        }); 
    }
 

fechtDataFactura(){
    console.log("holaaaa");
}

render(){
    return(
     <div className="col-lg-12 grid-margin stretch-card">
        <div className="card card-default">
            <div className="card-body">
            <h6 className="card-title">FACTURA ELECTRÓNICA:</h6>
            <form method="post" role="form" data-toggle="validator" id="frm_editar_producto">
                <div className="form-group">
                    <div className="row">
                        <div className="col-3">
                            <label><i className="mdi mdi-barcode"></i>   Serie:</label>
                            <input type="hidden" name="tipoComprobante" id="tipoComprobante" value="{{ $tiposcomprobante->id }}" className="form-control" readonly="readonly" />
                            <input type="text" name="serieVenta" id="serieVenta" value={this.props.dataFactura.serie} className="form-control" readonly="readonly"/>
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-file-document-box"></i>    Nº Factura:</label>
                            <input type="number" className="form-control" name="facturaVenta" id="facturaVenta" value={this.props.correlativo} readOnly="readonly" />
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-calendar-text"></i>    Fecha de emisión:</label>
                            <input type="text" className="form-control" value ={this.props.fecha} name="dateFactura" id="dateFactura"/>
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-cash-multiple"></i>  Moneda:</label>
                            <input type="text" className="form-control" value ="Soles" name="dateFactura" id="dateFactura" readOnly="readonly" />
                        </div>
                    </div>
                </div>
                <h6 className="card-title">CLIENTE:</h6>
                <div className="form-group" >
                    <div className="row">
                        <div className="col-3">
                            <label>  <i className="mdi mdi-account-card-details"></i>   Tipo de Documento: *</label>
                            <div className="input-group">
                                <select className="form-control" name="tipoDocumento" id="tipoDocumento" style={{width: '100%'}}>
                                    <option value="3" > RUC </option>
                                </select>
                            </div>
                        </div>
                        <div className="col-3">
                            <label>N° de Documento:*</label>
                            <input type="number" className="form-control" name="numeroDocumento" id="numeroDocumento"/>
                        </div>
                        <div className="col-6">
                            <label>Razón Social: *</label>
                            <input type="text" className="form-control" name="razonSocial" id="razonSocial"/>
                        </div>
                    </div>
                </div>
                <div className="form-group" >
                    <div className="row">
                        <div className="col-12">
                            <label><i className="mdi mdi-home"></i>    Dirección: *</label>
                            <input type="text" className="form-control" name="direccion" id="direccion"/>
                        </div>
                    </div>
                </div>
                <h6 className="card-title">DETALLE DOCUMENTO:</h6>
                <div className="row">
                <div className="col-12">
                    <table className="table table-bordered dataTables_length" id="tb_products">
                        <thead>
                            <tr>
                                
                                <th width="25%;">Producto</th>
                                <th>Cantidad</th>
                                <th>Disponible</th>
                                <th width="15%;">Precio</th>
                                <th>Descuento</th>
                                <th>SubTotal</th>
                                <th>Total</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
                <div className="col-12">
                    <br/>
                    
                    <button type="button" className="btn btn-warning" id="btnAddProduct" onClick={() => this.cambiara()} >
                        <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO AL DETALLE
                    </button>
                    <br/>
                </div>
            </div><br/>
            <div className="col-md-12" style={{"margin-top": '15px'}}>
                <div className="row">
                    <div className="col-sm-7">
                        <div className="content-group">
                            <h6>Observación:</h6>
                            <textarea className="form-control" name="observacionVenta" id="exampleTextarea1"  rows="8" placeholder="Escribe aquí una observación">SN</textarea>
                        </div>
                    </div>
                    <div className="col-sm-5">
                        <div className="content-group">
                            <h6>Resumen:</h6>
                            <div className="table-responsive no-border">
                                <table className="table">
                                    <tbody>
                                        <tr>
                                            <th>Descuento:</th>
                                            <td className="text-right">
                                                S/. <span id="descuentoVentaTexto">0</span>
                                                <input type="hidden" name="descuentoVenta" id="descuentoVenta" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td className="text-right">
                                                S/. <span id="subTotalVentaTexto">0</span>
                                                <input type="hidden" name="subTotalVenta" id="subTotalVenta" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>IGV: <span className="text-regular">(18%)</span></th>
                                            <td className="text-right">
                                                S/. <span id="igvVentaTexto">0</span>
                                                <input type="hidden" name="igvVenta" id="igvVenta" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td className="text-right text-primary"><h5 className="text-semibold">
                                                S/. <span id="totalVentaTexto">0</span></h5>
                                                <input type="hidden" name="totalVenta" id="totalVenta" value="2"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div className="form-group boton">
                <div className="row">
                    <div className="col-6">
                        <button type="button" className=" addexis form-control btn btn-block btn-primary btn-lg" id="emitirFactura">
                            EMITIR FACTURA ELECTRÓNICA</button>
                    </div>
                    <div className="col-6">
                        <button type="button" className=" addexis form-control btn btn-block btn-success btn-lg" id="guardarFactura">
                            GUARDAR FACTURA ELECTRÓNICA</button>
                    </div>
                </div>
                
            </div>
        </form>

        <div id="agregarProductoDetalleModal" className="modal fade bd-agregarProductoDetalleModal-lg" role="dialog">
            <div className="modal-dialog modal-lg">
                <div className="modal-content">
                    <div className="card card-default">
                        <div className="card-header cabezera">
                            <button type="button" className="close" data-dismiss="modal">&times;</button>
                            <h4> Buscar producto especifico </h4>
                        </div>
                        <div className="modal-body">
                            
                                <div className="card">
                                    <div className="card-body">
                                        <h4 className="card-title">Buscar</h4>
                                        <div className="row">
                                            <div className="col-3">
                                                <label>Buscar Codigo</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_codigo" id="buscar_tb_codigo"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Marca</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_marca" id="buscar_tb_marca"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Tipo</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_tipo" id="buscar_tb_tipo"/>
                                            </div>
                                            <div className="col-3">
                                                <label>Buscar Nombre</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_nombre"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Precio</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_precio"/>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <button type="button" className="btn btn-warning" id="btnAddProductoTemporal"  >
                                    <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO TEMPORAL
                                </button>


                            <div className="card-body" id="agregarProductoDetalleModalBody">
                                <table className="table table-bordered dataTables_length" id="tb_buscarProducto" style={{width:'100%'}}>
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Marca</th>
                                            <th>Tipo</th>
                                            <th>Disponibles</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                </table>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        
      <Modal
            size="lg"
            show={this.state.estadoa}
            onHide={() => this.cambiara()}            
        >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
             Buscar producto especifico
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
            <div className="card card-default">
                        <div className="card-header cabezera">
                            <h4> Buscar producto especifico </h4>
                        </div>
                        <div className="modal-body">
                            
                                <div className="card">
                                    <div className="card-body">
                                        <h4 className="card-title">Buscar</h4>
                                        <div className="row">
                                            <div className="col-3">
                                                <label>Buscar Codigo</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_codigo" id="buscar_tb_codigo"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Marca</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_marca" id="buscar_tb_marca"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Tipo</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_tipo" id="buscar_tb_tipo"/>
                                            </div>
                                            <div className="col-3">
                                                <label>Buscar Nombre</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_nombre"/>
                                            </div>
                                            <div className="col-2">
                                                <label>Buscar Precio</label>
                                                <input type="text" className="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_precio"/>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <button type="button" className="btn btn-warning" id="btnAddProductoTemporal" onClick={() => this.cambio()} >
                                    <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO TEMPORAL
                                </button>


                            <div className="card-body" id="agregarProductoDetalleModalBody">
                                <table className="table table-bordered dataTables_length" id="tb_buscarProducto" style={{width:'100%'}}>
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Marca</th>
                                            <th>Tipo</th>
                                            <th>Disponibles</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                </table>  
                            </div>
                        </div>
                    </div>

        </Modal.Body>
      </Modal>

      <Modal
            show={this.state.estadob}
            onHide={() => this.cambiarb()}
        >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
            Custom Modal Styling
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
                <div className="card card-default">
                        <div className="card-header cabezera">
                            <h4> Agregar Producto </h4>
                        </div>
                        <div className="modal-body">
                            <div className="card-body">
                                <form method="post" role="form" data-toggle="validator" id="frm_producto">
                                    @csrf
                                    <div className="form-group" >
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Codigo (OPCIONAL)</label>
                                                <input type="text" className="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo"/>
                                            </div>
                                            <div className="col-12" id="alertaCodigo">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Nombre del producto</label>
                                                <input type="text" className="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Precio con IGV(18%)</label>
                                                <input type="text" name="precioVentaProducto" id="precioVentaProducto" className="form-control"
                                                    pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00" />
                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group boton">
                                        <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                            Agregar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

        </Modal.Body>
      </Modal>

        <div id="productoTemporalModal" className="modal fade bd-productoTemporalModal" role="dialog">
            <div className="modal-dialog ">
                <div className="modal-content">
                    <div className="card card-default">
                        <div className="card-header cabezera">
                            <button type="button" className="close" data-dismiss="modal">&times;</button>
                            <h4> Agregar Producto </h4>
                        </div>
                        <div className="modal-body">
                            <div className="card-body">
                                <form method="post" role="form" data-toggle="validator" id="frm_producto">
                                    @csrf
                                    <div className="form-group" >
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Codigo (OPCIONAL)</label>
                                                <input type="text" className="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo"/>
                                            </div>
                                            <div className="col-12" id="alertaCodigo">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Nombre del producto</label>
                                                <input type="text" className="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Precio con IGV(18%)</label>
                                                <input type="text" name="precioVentaProducto" id="precioVentaProducto" className="form-control"
                                                    pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00" />
                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group boton">
                                        <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                            Agregar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>


)}}

export default VentaFactura