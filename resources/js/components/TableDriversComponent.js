import axios from 'axios';
import React, { useEffect, useState} from 'react';
import env  from '../env';
import CRUDTable, {
    Fields,
    Field,
    CreateForm,
    UpdateForm,
    DeleteForm
} from "react-crud-table";
import Swal from 'sweetalert2';
import "./index.css";

const styles = {
    container: { margin: "auto", width: "fit-content" },
};

const TableDriversComponent = () => {

    const [ drivers, setDrivers ] = useState([]);

    useEffect(() => {
        const fetchDrivers = async () => {
            const userId = document.querySelector("meta[name='user-id']").getAttribute("content");
            await axios(`/manager/drivers/fetch/${userId}`)
                .then(response => {
                    setDrivers(response.data);
                }).catch(err => console.log(err))
        }

        fetchDrivers();

    }, []);


    const service = {
        fetchItems: payload => {
            const result = drivers.sort(getSorter(payload.sort));
            return Promise.resolve(result);
        },
        create: drive => {

            Swal.fire({
                title: 'Guardando',
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            });

            let formData = new FormData();
            const userId = document.querySelector("meta[name='user-id']").getAttribute("content");
            formData.append('id', userId);
            formData.append('avatar', drive.imageprofile);
            formData.append('name', drive.name);
            formData.append('email', drive.email);
            formData.append('plate_number', drive.plate_number);

            const asyncCall = async () => {

                let result;

                await axios.post('/manager/drivers/add', formData)
                    .then(res => {
                        if(res.data.errors){
                            result = res.data.errors;
                        }else{
                            drive = {...drive, ...res.data}
                        }
                    })
                    .catch(err => {});

                if(result)
                    return Promise.reject(result.join());

                setDrivers([...drivers,  {
                    ...drive
                }]);

                Swal.close();

                return Promise.resolve(drive);
            }

            return asyncCall();
        },
        update: data => {

            Swal.fire({
                title: 'Actualizando',
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            });

            const drive = drivers.find(t => t.id === data.id);
            let formData = new FormData();
            formData.append('id', data.id);
            formData.append('avatar', data.imageprofile);
            formData.append('name', data.name);
            formData.append('email', data.email);
            formData.append('plate_number', data.plate_number);

            const asyncCall = async () => {

                let result = [];

                await axios.post(`/manager/drivers/${data.id}`, formData)
                    .then(res => {
                        if(res.data.errors){
                            result = res.data.errors;
                        }else{
                            drive.avatar = res.data.avatar;
                        }
                    })
                    .catch(err => {});

                if(Object.keys(result).length !== 0)
                    return Promise.reject(result.join());

                drive.name = data.name;
                drive.email = data.email;
                drive.plate_number = data.plate_number;
                Swal.close();
                return Promise.resolve(drive);
            }

            return asyncCall();

        },
        delete: data => {

            Swal.fire({
                title: 'Eliminando',
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            });

            const drive = drivers.find(t => t.id === data.id);
            axios.delete(`/manager/drivers/${data.id}`)
                .then(res => {
                    window.location.reload();
                }).catch(err => {});
            return Promise.resolve(drive);
        }
    };

    const getSorter = data => {
        const mapper = x => x[data.field];
        let sorter = SORTERS.STRING_ASCENDING(mapper);

        if (data.field === "id") {
            sorter =
                data.direction === "ascending"
                    ? SORTERS.NUMBER_ASCENDING(mapper)
                    : SORTERS.NUMBER_DESCENDING(mapper);
        } else {
            sorter =
                data.direction === "ascending"
                    ? SORTERS.STRING_ASCENDING(mapper)
                    : SORTERS.STRING_DESCENDING(mapper);
        }

        return sorter;
    };

    const SORTERS = {
        NUMBER_ASCENDING: mapper => (a, b) => mapper(a) - mapper(b),
        NUMBER_DESCENDING: mapper => (a, b) => mapper(b) - mapper(a),
        STRING_ASCENDING: mapper => (a, b) => mapper(a).localeCompare(mapper(b)),
        STRING_DESCENDING: mapper => (a, b) => mapper(b).localeCompare(mapper(a))
    };

    const Avatar = ({field, form}) => {

        const uploadAvatar = (e) => {
            const files = e.target.files;
            form.setFieldValue(field.name, files[0]);
            form.setFieldValue('imageprofile', files[0]);
        }

        return (
            <>
                {
                    field.value ?
                        <div>
                            <img alt={field.name} width="200px" src={env.pathRelativeStorage() + field.value} />
                        </div>:null
                }
                <div>
                    <div className="file-field input-field">
                        <div className="btn">
                            <span>Subir Imagen</span>
                            <input type="file" onChange={uploadAvatar} name={field.name} id={field.name} accept=".jpg, .jpeg, .png" />
                        </div>
                        <div className="file-path-wrapper">
                            <input className="file-path validate" type="text" />
                        </div>
                    </div>
                </div>
            </>
        );
    }

    const validateEmail = (email) => {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    const  Table = () => {
        return(
            <div style={styles.container}>
                <CRUDTable
                    caption="Conductores"
                    fetchItems={payload => service.fetchItems(payload)}
                    actionsLabel="Acciones"
                >
                    <Fields>
                        <Field name="id" label="Id" hideInCreateForm hideInUpdateForm />
                        <Field name="avatar" label="Foto de perfil" render={(field) => <Avatar {...field} />} />
                        <Field name="name" label="Nombre y apellidos" placeholder="Nombre y apellidos" />
                        <Field name="email" label="Email" placeholder="Email de Facebook o Google" />
                        <Field name="plate_number" type="text" label="Número de placa" placeholder="Número de placa" />
                    </Fields>
                    <CreateForm
                        title="Agregar conductor"
                        message="Agregar un nuevo conductor!"
                        generalErrorMessage="Hay algunos errores"
                        trigger="Agregar conductor"
                        onSubmit={async(drive) => service.create(drive)}
                        submitText="Agregar"
                        validate={values => {
                            const errors = {};

                            if (!values.name || !/\s/.test(values.name)) {
                                errors.name = "Por favor, escriba el nombre y apellidos del conductor";
                            }

                            if (!values.email || !validateEmail(values.email)) {
                                errors.email = "Por favor, escriba un email de uso con Facebook o Google";
                            }

                            if (!values.plate_number) {
                                errors.plate_number = "Por favor, escriba el número de placa del vehiculo asignado al conductor";
                            }

                            if (values.plate_number && (values.plate_number.length > 6 || values.plate_number.length < 6)) {
                                errors.plate_number = "Por favor, escriba un número de placa válido"
                            }

                            return errors;
                        }}
                    />

                    <UpdateForm
                        title="Actualizar"
                        message="Actualizar conductor"
                        generalErrorMessage="Hay algunos errores"
                        trigger="Actualizar"
                        onSubmit={async(drive) => service.update(drive)}
                        submitText="Actualizar"
                        validate={values => {
                            const errors = {};

                            if (!values.name || !/\s/.test(values.name)) {
                                errors.name = "Por favor, escriba el nombre y apellidos del conductor";
                            }

                            if (!values.email || !validateEmail(values.email)) {
                                errors.email = "Por favor, escriba un email de uso con Facebook o Google";
                            }

                            if (!values.plate_number) {
                                errors.plate_number = "Por favor, escriba el número de placa del vehiculo asignado al conductor";
                            }

                            if (values.plate_number && (values.plate_number.length > 6 || values.plate_number.length < 6)) {
                                errors.plate_number = "Por favor, escriba un número de placa válido"
                            }

                            return errors;
                        }}
                    />

                    <DeleteForm
                        title="Eliminar conductor"
                        message="¿Seguro, quieres eliminar ?"
                        generalErrorMessage="Hay algunos errores"
                        trigger="Eliminar"
                        onSubmit={drive => service.delete(drive)}
                        submitText="Eliminar"
                        validate={values => {
                            const errors = {};
                            if (!values.id) {
                                errors.id = "Please, provide id";
                            }
                            return errors;
                        }}
                    />
                </CRUDTable>
            </div>
        );
    }

    return(<Table/>);
}

TableDriversComponent.propTypes = {};

export default TableDriversComponent;

