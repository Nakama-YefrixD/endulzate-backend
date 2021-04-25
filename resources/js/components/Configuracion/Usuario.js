import React from 'react';
import {Component} from 'react';
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'


  class Usuario extends Component {

      constructor(){
          super();
          this.state ={

            usuario_tb:[],
            sucursales:[],
            sucursalesPArray:[],
            editarNombreUsuario:'',
            editarIdUsuario:'',

            //modales
            estadoModalAgregarUsuario:false,
            estadoModalEditarUsuario:false,
            //Toast
            estadoToastRed:false,
            estadoToastGreen:false,
            messageRed:'',
            messageGreen:'',
            //botones
            estadoBoton:[],
            //query
            estadoQuery:'',
            //Crear usuario
            crearNombre:'',
            crearPassword:'',
            crearUsername:'',
            crearSucursales:[],
            crearSucursalPredeterminada:'',
            //Editar usuario
            editarId:'',
            editarNombre:'',
            editarUsername:'',
            editarPassword:'',
            editarSucursales:[],
            editarSucursalPredeterminada:'',

            //DNIdata
            dniData:'',
            dniUsuario:''

          }
          this.fetchDataTable = this.fetchDataTable.bind(this);
          this.handleChange = this.handleChange.bind(this);
          this.handleChangeDni = this.handleChangeDni.bind(this);
          this.addSucursal = this.addSucursal.bind(this);
          this.predeterminarSucursal = this.predeterminarSucursal.bind(this);

      }

      cleanInputs(op){

        if (op == "crear"){
          this.setState({
            crearNombre:'',
            crearPassword:'',
            crearUsername:'',
            crearSucursales:[],
            crearSucursalPredeterminada:''
          })
        }
        else if(op == "editar"){
        //Editar usuario
        this.setState({
          editarId:'',
          editarNombre:'',
          editarUsername:'',
          editarPassword:'',
          editarSucursalPredeterminada:''
        })

        }

        else {
          this.setState({
                usuario_tb:[],
                sucursales:[],
                editarNombreUsuario:'',
                editarIdUsuario:''

            })
          }
        }

      cambiarToastRed(message) {
          this.setState({
              messageRed: message,
              estadoToastRed: !this.state.estadoToastRed
          });
      }
      cambiarToastGreen(message) {
          this.setState({
              messageGreen: message,
              estadoToastGreen: !this.state.estadoToastGreen
          });
      }

      cambiarModalAgregarUsuario(){
          this.setState({
              estadoModalAgregarUsuario: !this.state.estadoModalAgregarUsuario,
              crearSucursales:[],
          })
      }

      cambiarModalEditarUsuario(operacion,id,nombre,username,sucursales,predeterminada){

        if(operacion == "editar"){
          var x = [];
          console.log(sucursales);
          let c = ()=>{
              sucursales.map(task=>{
                 var a = task.pivot.sucursal_id
                 x.push(a.toString());
                 console.log(task);
                  })
              return x;
          }

          this.setState({
              estadoModalEditarUsuario: !this.state.estadoModalEditarUsuario,
              editarId: id,
              editarNombre: nombre,
              editarUsername: username,
              editarSucursales: c(),
              editarSucursalPredeterminada: predeterminada

          },()=>{
                console.log(this.state.editarSucursales)
                this.setState({
                  crearSucursales: this.state.editarSucursales
                })
                }
            )

        }
       else{
         this.setState({
             estadoModalEditarUsuario: !this.state.estadoModalEditarUsuario,
             editarSucursales:[]

         })
       }
      }
      sendCrearUsuario(){

              fetch(`/configuraciones/usuarios/crear`,
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      nombre: this.state.crearNombre,
                      username: this.state.crearUsername,
                      password: this.state.crearPassword,
                      sucursales: this.state.crearSucursales,
                      predeterminado: this.state.crearSucursalPredeterminada

                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',
                  }
              }
          )
          .then(res => res.json())
          .then(data => {
              if(data.response==true){
                console.log("exito");
                this.cambiarToastGreen("Se creo exitosamente el usuario");
                this.cleanInputs("crear");
                this.fetchDataTable();
              }
              else{
                console.log("error");
                this.cambiarToastRed("Error en crear usuario");
              }

          })
          .catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("Error en crear usuario");
          });  }

      sendEditarUsuario(){

                  fetch(`/configuraciones/usuarios/editar`,
                  {
                      method: 'POST',
                      body: JSON.stringify({
                          '_token': csrf_token,
                          id: this.state.editarId,
                          nombre: this.state.editarNombre,
                          username: this.state.editarUsername,
                          password: this.state.editarPassword,
                          sucursales: this.state.crearSucursales,
                          predeterminado: this.state.editarSucursalPredeterminada

                      }),
                      headers: {
                          'Accept' : 'application/json',
                          'Content-Type': 'application/json',
                      }
                  }
              )
              .then(res => res.json())
              .then(data => {
                  if(data.response==true){
                    console.log("exito");
                    this.cambiarToastGreen("se edito el usuario");
                    this.cleanInputs("editar");
                    this.fetchDataTable();
                  }
                  else{
                    console.log("error");
                    this.cambiarToastRed("Error en editar usuario");
                  }

              })
              .catch((error)=> {
                console.log('Hubo un problema con la petición Fetch:' + error.message);
                this.cambiarToastRed("Error en editar usuario");
              });  }

      deleteUsuario(id){
            console.log(id);
                fetch(`/almacen/marcas/eliminar`,{
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        id: id
                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-type' : 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data =>{
                    if(data.response==true){
                      console.log("exito");
                      this.cambiarToastGreen("en eliminar Marca");
                      this.fetchDataTable();
                    }
                    else{
                      console.log("error");
                      this.cambiarToastRed("en eliminar Marca");
                    }
                })
                .catch((error)=> {
                  console.log('Hubo un problema con la petición Fetch:' + error.message);
                  this.cambiarToastRed("en eliminar Marca");
                });

      }


    addSucursal(e){

      const {checked , value} = e.target;

          if(checked==true){
            var x = [...this.state.crearSucursales];
            x.push(value);
            this.setState({
              crearSucursales: x,
              editarSucursales: x
            },()=>{
                if((this.state.editarSucursales).length == 1){
                  this.setState({
                    editarSucursalPredeterminada: this.state.editarSucursales[0],
                    crearSucursalPredeterminada: this.state.editarSucursales[0]
                  })
                }
            }
          )

          }
          else{
            console.log("deslogeado");
            var x = [...this.state.crearSucursales];
            var index = x.findIndex(i=> i === value);
            if(index>-1){
              x.splice(index,1);
              this.setState({
                crearSucursales: x,
                editarSucursales: x
              },()=>{
                  if((this.state.editarSucursales).length == 1){
                    this.setState({
                      editarSucursalPredeterminada: this.state.editarSucursales[0],
                      crearSucursalPredeterminada: this.state.editarSucursales[0]
                    })
                  }
                })

          }
        }
}
    handleChangeDni(e){
      const {name , value} = e.target;

      if(value.length == 8){
          this.setState({
              [name] : value

          }, () => {
              this.fetchDNI();
          })
          console.log(value);
      }
      console.log(name);
      console.log(value);
    }

    fetchDNI(){

        fetch(`/consult/dni/${this.state.dniUsuario}`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                }
            }
        )

        .then(res => res.json())
        .then(data => {
            console.log(data);
            this.setState({dniData: data},
              ()=>this.dniOpe()

            );
    })

}
    dniOpe(){
      let a = this.state.dniData;
      let y = a.persona.nombres +' '+ a.persona.apellidoMaterno +' '+ a.persona.apellidoPaterno

      this.setState({
        crearNombre: y
      })
    }


    fetchDataTable(){
      fetch('/configuracion/usuarios/tb_usuarios')
        .then(res => res.json())
        .then(data => {
            this.setState({usuario_tb: data},()=>{
              console.log(this.state.usuario_tb)
            });
      })
      fetch('/configuracion/usuarios/tb_sucursales')
        .then(res => res.json())
        .then(data => {
            this.setState({
              sucursales: data,
              sucursalesPArray: data
            },()=>{
              console.log(this.state.sucursales)
              //this.predeterminarSucursal();
            });
      })
    }
  predeterminarSucursal(){
    let x = []
    let c = ()=>{
        this.state.sucursales ?
        this.state.sucursales.map(task=>{
           x.push(task.id);
        }) :null
        return x;
    }
    this.setState({
      sucursalesPArray: c()
    },()=>console.log(this.state.sucursalesPArray))

  }


    handleChange (e){
      const {name , value} = e.target;
      this.setState({
          [name] : value

      })
      console.log(name);
      console.log(value);
    }
    handleChangeBuscador (e){
      const {name , value} = e.target;
      console.log(value);
      this.fetchQuery(value);
      console.log(name);

    }

    componentDidMount(){
      this.fetchDataTable();
    }



   render (){
     return(
       <div className="row">

          <div className="col-lg-12 grid-margin stretch-card">
             <div className="card">
                 <div className="card-body">
                     <h4 className="card-title">Acciones</h4>
                     <button type="button"
                         onClick={()=>this.cambiarModalAgregarUsuario()}
                         className="btn waves-effect waves-light btn-info">Agregar Usuario</button>
                 </div>
             </div>
         </div>

         <div className="col-lg-12 grid-margin stretch-card">
             <div className="card">
                 <div className="card-body">
                     <h4 className="card-title">Usuarios</h4>
                     <table id="tb_usuarios" className="table table-striped" style={{width:'100%'}}>
                         <thead>
                             <tr>
                                 <th>Codigo</th>
                                 <th>Nombre</th>
                                 <th>Username</th>
                                 <th>Sucursales</th>
                                 <th>Opciones</th>
                             </tr>
                         </thead>
                         <tbody>
                              {
                                 this.state.usuario_tb ?
                                 this.state.usuario_tb.map(task =>{
                                     return (
                                         <tr key={task.id}>
                                             <td>{task.id}</td>
                                             <td>{task.name}</td>
                                             <td>{task.username}</td>
                                             <td>{task.sucursales.map(s =>{
                                                return(<div>{s.nombre}</div>)
                                             })}</td>
                                             <td><button
                                             className="btn btn-sm btn-secondary editar" type="button" onClick={()=>this.cambiarModalEditarUsuario("editar",task.id,task.name,task.username,task.sucursales,task.predeterminado)}><i className="mdi mdi-lead-pencil"></i></button>
                                             <button className="btn btn-sm btn-danger eliminar" type="button" onClick={()=>this.deleteUsuario(task.id)}><i className="mdi mdi-delete"></i></button>
                                             </td>

                                         </tr>
                                     );
                                  } )   : null
                                 }
                         </tbody>
                           <div className="container">
                               <div className="row justify-content-end">
                                 <div className="col-4">

                                   {
                                     this.state.estadoBoton?
                                     this.state.estadoBoton.map(task =>{
                                         return (
                                             <button key={task}
                                                 className="btn btn-sm btn-secondary editar" type="button" onClick={()=>this.cambiarPaginate(task)}>{task}</button>

                                         );
                                      } )   : null
                                    }
                                </div>
                              </div>
                           </div>
                     </table>
                 </div>
             </div>
         </div>


         <Modal

             show={this.state.estadoModalAgregarUsuario}
             onHide={() => this.cambiarModalAgregarUsuario()}
             >
             <Modal.Header closeButton>
               <Modal.Title id="example-custom-modal-styling-title">
                 Agregar Usuario
               </Modal.Title>
             </Modal.Header>
             <Modal.Body>
                   <div className="card card-default">
                       <div className="modal-body">
                           <div className="card-body">
                                   <div className="form-group">
                                       <label htmlFor="exampleInputUsername1">Dni</label>
                                       <input type="text" className="form-control" name="dniUsuario" onChange={this.handleChangeDni}/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputUsername1">NOMBRES COMPLETOS</label>
                                       <input type="text" className="form-control" name="crearNombre" onChange={this.handleChange} value={this.state.crearNombre} placeholder="NOMBRE Y APELLIDOS"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputEmail1">USERNAME</label>
                                       <input type="email" className="form-control" name="crearUsername" onChange={this.handleChange} value={this.state.crearUsername} placeholder="Username"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputPassword1">CONTRASEÑA</label>
                                       <input type="password" className="form-control" name="crearPassword" onChange={this.handleChange} value={this.state.crearPassword} placeholder="**********"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputPassword1">Sucursales</label>
                                       {
                                         this.state.sucursales?
                                         this.state.sucursales.map(s =>{
                                         return(
                                            <div><input type="checkbox" name="sucursalCrear" value={s.id} onClick={this.addSucursal}/> {s.nombre}</div>
                                         )
                                       }) : null

                                     }

                                   </div>
                                   <div className="form-group">
                                       <label>Seleccionar sucursal predeterminada</label>
                                       <select className="form-control listProductos" name="crearSucursalPredeterminada" onChange={this.handleChange} value={this.state.crearSucursalPredeterminada}>

                                           {
                                               this.state.sucursalesPArray ?
                                               this.state.sucursalesPArray.map((data)=>{
                                                 if ((this.state.editarSucursales.findIndex(a=>a==data.id)) > -1){
                                                   return(
                                                    <option key={data.id} value={data.id} > {data.nombre} </option>
                                                   )
                                                 }

                                               }
                                               )
                                               :null
                                           }

                                       </select>
                                   </div>
                                   <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" onClick={()=>this.sendCrearUsuario()}>Crear</button>
                           </div>
                       </div>
                   </div>

             </Modal.Body>
           </Modal>

           <Modal

               show={this.state.estadoModalEditarUsuario}
               onHide={() => this.cambiarModalEditarUsuario()}
               >
               <Modal.Header closeButton>
                 <Modal.Title id="example-custom-modal-styling-title">
                   Editar Usuario
                 </Modal.Title>
               </Modal.Header>
               <Modal.Body>
                   <div className="card card-default">
                       <div className="modal-body">
                           <div className="card-body">
                                   <div className="form-group">
                                       <label htmlFor="exampleInputUsername1">NOMBRES COMPLETOS</label>
                                       <input type="text" className="form-control" name="editarNombre"   value={this.state.editarNombre} onChange={this.handleChange}  placeholder="NOMBRE Y APELLIDOS"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputEmail1">USERNAME</label>
                                       <input type="email" className="form-control" name="editarUsername"  value={this.state.editarUsername} onChange={this.handleChange} placeholder="Email"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputPassword1">CONTRASEÑA</label>
                                       <input type="password" className="form-control" name="editarPassword"  value={this.state.editarPassword} onChange={this.handleChange} placeholder="**********"/>
                                   </div>
                                   <div className="form-group">
                                       <label htmlFor="exampleInputPassword1">Sucursales</label>
                                       {
                                         this.state.sucursales?
                                         this.state.sucursales.map(s =>{
                                         return(
                                           <div>
                                            {
                                              ((this.state.editarSucursales.findIndex(a=>a==s.id)) > -1) ?
                                            <div><input type="checkbox" name="sucursalEditar" value={s.id} defaultChecked onClick={this.addSucursal}/> {s.nombre}</div>

                                            :
                                            <div><input type="checkbox" name="sucursalEditar" value={s.id} onClick={this.addSucursal}/> {s.nombre}</div>

                                            }
                                          </div>

                                         )
                                       }) : null

                                     }

                                   </div>
                                   <div className="form-group">
                                       <label>Seleccionar sucursal predeterminada</label>
                                       <select className="form-control listProductos" name="editarSucursalPredeterminada" onChange={this.handleChange} value={this.state.editarSucursalPredeterminada}>

                                           {
                                               this.state.sucursalesPArray ?
                                               this.state.sucursalesPArray.map((data,index)=>{
                                                 if ((this.state.editarSucursales.findIndex(a=>a==data.id)) > -1){
                                                   return(
                                                    <option key={data.id} value={data.id} > {data.nombre} </option>
                                                      )
                                                 }

                                               }
                                               )
                                               :null
                                           }

                                       </select>
                                   </div>
                                   <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" onClick={()=>this.sendEditarUsuario()}>Editar</button>
                           </div>
                       </div>
                   </div>

               </Modal.Body>
             </Modal>

         <Toast
               style={{
               position: 'fixed',
               top: 5,
               right: 5,
               zIndex:1060,
               backgroundColor: 'rgba(76,208,76,0.7)',
               color:'white'
               }}
               onClose={() => this.cambiarToastGreen()}
               show={this.state.estadoToastGreen}
               delay={3000}
               autohide
               >
                 <Toast.Body>
                   <h4>{this.state.messageGreen}</h4>
                 </Toast.Body>
           </Toast>

         <Toast
                 style={{
                 position: 'fixed',
                 top: 5,
                 right: 5,
                 zIndex:1061,
                 backgroundColor: 'rgba(205,55,55,0.7)',
                 color:'white'
                   }}
                 onClose={() => this.cambiarToastRed()}
                 show={this.state.estadoToastRed}
                 delay={3000}
                 autohide

           >
             <Toast.Body>
               <h4>{this.state.messageRed}</h4>
             </Toast.Body>
         </Toast>


         </div>

       )

     }
  }


export default Usuario
