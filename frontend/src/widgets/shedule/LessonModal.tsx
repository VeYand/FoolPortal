import {Modal, Form, Input, DatePicker, Select, Button} from 'antd'
import {LessonData, LocationData} from '../../shared/types'

type LessonModalProps = {
	selectedLesson?: LessonData,
	unselectLesson: () => void,
	locations: LocationData[],
	onSave: (lesson: Partial<LessonData>) => void,
}

const LessonModal = ({selectedLesson, unselectLesson, locations, onSave}: LessonModalProps) => {
	const [form] = Form.useForm()

	const handleSubmit = () => {
		form.validateFields()
			.then(values => {
				onSave({...selectedLesson, ...values})
				unselectLesson()
				form.resetFields()
			})
			.catch(info => {
				console.error('Validation failed:', info)
			})
	}

	return (
		<Modal
			title={selectedLesson ? 'Редактировать пару' : 'Создать пару'}
			open={!!selectedLesson}
			onCancel={() => {
				unselectLesson()
				form.resetFields()
			}}
			footer={[
				<Button key="cancel" onClick={unselectLesson}>
					Отмена
				</Button>,
				<Button key="submit" type="primary" onClick={handleSubmit}>
					Сохранить
				</Button>,
			]}
		>
			<Form
				form={form}
				initialValues={selectedLesson ? {...selectedLesson} : {}}
				layout="vertical"
			>
				<Form.Item
					name="date"
					label="Дата"
					rules={[{required: true, message: 'Пожалуйста, выберите дату!'}]}
				>
					<DatePicker />
				</Form.Item>
				<Form.Item
					name="startTime"
					label="Время начала"
					rules={[{required: true, message: 'Пожалуйста, введите время начала!'}]}
				>
					<Input type="time" />
				</Form.Item>
				<Form.Item
					name="duration"
					label="Продолжительность (минуты)"
					rules={[{required: true, message: 'Пожалуйста, укажите продолжительность!'}]}
				>
					<Input type="number" />
				</Form.Item>
				<Form.Item
					name="locationId"
					label="Аудитория"
					rules={[{required: true, message: 'Пожалуйста, выберите аудиторию!'}]}
				>
					<Select>
						{locations.map(location => (
							<Select.Option key={location.locationId} value={location.locationId}>
								{location.name}
							</Select.Option>
						))}
					</Select>
				</Form.Item>
				<Form.Item name="description" label="Описание">
					<Input.TextArea />
				</Form.Item>
			</Form>
		</Modal>
	)
}

export {LessonModal}
