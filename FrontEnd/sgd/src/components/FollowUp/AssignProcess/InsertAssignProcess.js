import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';
import { Notify } from '../../Modal/Notify';

export const InsertAssignProcess = ({ selectedDataRow,closeModalInsert }) => {

    const { loginData } = useContext(AuthContext);
    const [selectedDataProcess, setSelectedDataProcess] = useState(null);
    const [process, setProcess] = useState([]);

    const [data, setData] = useState({
        _process: "",
        _article: selectedDataRow.article_id
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataProcess = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiProcess/apiProcess.php/listProcessReference',
                requestData
            );

            setProcess(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataProcess();
    }, []);

    const handleInsert = async () => {

        if (data._process === '') {
            handleShowError();
            return;
        }

        try {
            const requestData = {
                _process: data._process,
                _article: data._article
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiArticleProcess/apiArticleProcess.php/insertArticleProcess',
                requestData
            );
            
            handleShowSuccess();
            setTimeout(() => {
                closeModalInsert();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const optionsProcess = process.map((data) => ({
        value: data.process_id,
        label: data.process_name,
    }));

    const handleChangeProcess = (event) => {
        setSelectedDataProcess(event || '');
        if (event && event.value) {
            setData({ ...data, _process: event.value });
        }
    };

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
            title={`Insertar proceso en artículo: ${selectedDataRow.article_name}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos de asignación de proceso</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="form-group col-sm-12">
                                    <label>Proceso</label>
                                    <Select
                                        onChange={handleChangeProcess}
                                        options={optionsProcess}
                                        placeholder={'Seleccione un proceso'}
                                        defaultValue={selectedDataProcess}
                                        isClearable
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            }
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalInsert}
            handleButton2Click={handleInsert}
            closeModalInsert={closeModalInsert}
        />
    )
}






