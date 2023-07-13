import { React, useEffect, useState } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import { Notify } from '../Modal/Notify';
import Select from 'react-select';


export const UpdateDepartment = ({ selectedDataRow, closeModalEdit }) => {

    const [selectedDataReference, setSelectedDataReference] = useState({ value: selectedDataRow.status_id, label: selectedDataRow.reference_name });
    const [references, setReferences] = useState([]);
    const [data, setData] = useState({
        departmentName: selectedDataRow.department_name,
        fatherId: selectedDataRow.father_id,
        statusId: selectedDataRow.department_status_id,
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'common.department',
                referenceField: 'status_id',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setReferences(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataReference();
    }, []);

    const handleUpdate = async () => {

        if (data.idClient === '' || data.departmentName === '' || data.fatherId === '' || data.statusId === '') {
            handleShowError();
            return;
        }
        try {
            const requestData = {
                idDepartment: selectedDataRow.department_id,
                nameDepartment: data.departmentName,
                fatherId: data.fatherId,
                _statusId: data.statusId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiDepartment/apiDepartment.php/updateDepartment',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalEdit();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const handleChangeName = (event) => {
        setData({ ...data, departmentName: event.target.value });
    };

    const handleChangePadre = (event) => {
        setData({ ...data, fatherId: event.target.value });
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, statusId: event.value });
        }
    };

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const handleShowError = () => {
        setNotifyError(true);
        setTimeout(() => {
            setNotifyError(false);
        }, 5000);
    };

    const handleShowSuccess = () => {
        setNotifySuccess(true);
        setTimeout(() => {
            setNotifySuccess(false);
        }, 2000);
    };

    return (
        <Modal
            title="Editar Departamento"
            body={
                <div className="card card-primary">
                    <div className="card-header">
                        <h3 className="card-title">Datos del departamento</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body">
                        <form>
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Id</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.department_id}
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.department_name}
                                            placeholder="Enter ..."
                                            onChange={handleChangeName}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Padre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.father_id}
                                            placeholder="Enter ..."
                                            onChange={handleChangePadre}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                    ></Select>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalEdit}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalEdit}
        />
    )
}






