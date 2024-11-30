import {Modal, Form, Input, Select, Button, Upload, message} from 'antd'
import {useEffect, useMemo, useState} from 'react'
import {GroupData, SubjectData, TeacherSubjectData, USER_ROLE, UserData} from 'shared/types'

const {Option} = Select

type UserFormModalProps = {
	user?: UserData,
	onClose: () => void,
	onSave: (user: UserData, assignedSubjects: TeacherSubjectData[], groupIds: string[]) => void,
	groups: GroupData[],
	subjects: SubjectData[],
	teacherSubjects: TeacherSubjectData[],
}

const UserFormModal = ({
	user,
	onClose,
	onSave,
	groups = [],
	subjects = [],
	teacherSubjects = [],
}: UserFormModalProps) => {
	const [form] = Form.useForm()
	const [isTeacher, setIsTeacher] = useState(user?.role === USER_ROLE.TEACHER)
	const [isStudent, setIsStudent] = useState(user?.role === USER_ROLE.STUDENT)
	const [selectedGroupIds, setSelectedGroupIds] = useState<string[]>(user?.groupIds ?? [])
	const [imageData, setImageData] = useState<string | undefined>(user?.imageSrc)

	const assignedSubjectIds = useMemo(
		() => teacherSubjects.filter(ts => ts.teacherId === user?.userId).map(ts => ts.subjectId),
		[teacherSubjects, user],
	)

	const handleRoleChange = (role: USER_ROLE) => {
		setIsTeacher(role === USER_ROLE.TEACHER)
		setIsStudent(role === USER_ROLE.STUDENT)
		if (role !== USER_ROLE.TEACHER) {
			form.setFieldsValue({subjects: []})
		}
		if (role !== USER_ROLE.STUDENT) {
			form.setFieldsValue({groups: []})
		}
	}

	useEffect(() => {
		if (user) {
			form.setFieldsValue({
				...user,
				subjects: assignedSubjectIds,
			})
		}
	}, [user, assignedSubjectIds, form])

	const handleFileChange = (file: File) => {
		if (!['image/png', 'image/jpeg'].includes(file.type)) {
			message.error('Только PNG и JPG изображения поддерживаются.')
			return false
		}
		if (file.size > 5 * 1024 * 1024) {
			message.error('Размер изображения не должен превышать 5 МБ.')
			return false
		}

		const reader = new FileReader()
		reader.onload = e => {
			setImageData(e.target?.result as string)
			message.success('Изображение загружено успешно.')
		}
		reader.readAsDataURL(file)
		return false
	}

	const handleSubmit = () => {
		form.validateFields().then(values => {
			const password = values.password?.length > 0 ? values.password : undefined
			const updatedUser = {...user, ...values, password, imageSrc: imageData}
			const updatedTeacherSubjects
				= values.role === USER_ROLE.TEACHER
					? values.subjects?.map((subjectId: string) => ({
						teacherSubjectId:
					teacherSubjects.find(
						ts => ts.subjectId === subjectId && ts.teacherId === user?.userId,
					)?.teacherSubjectId || `ts-${subjectId}-${user?.userId}`,
						teacherId: user?.userId || 'new-teacher-id', //похоже на хрень, нам нужно знать только про id предметов, поэтому удалить передачу teacherSubejcts
						subjectId,
					})) || []
					: []
			const updatedGroupIds = values.groups || []
			onSave(updatedUser, updatedTeacherSubjects, updatedGroupIds)
			onClose()
		})
	}

	return (
		<Modal
			title={user ? 'Редактировать пользователя' : 'Создать пользователя'}
			visible
			onCancel={onClose}
			footer={null}
		>
			<Form
				form={form}
				initialValues={user}
				onValuesChange={changedValues => {
					if (changedValues.role) {
						handleRoleChange(changedValues.role)
					}
					if (changedValues.groupIds) {
						setSelectedGroupIds(changedValues.groupIds)
					}
				}}
			>
				<Form.Item
					name="firstName"
					label="Имя"
					rules={[{required: true, message: 'Пожалуйста, введите имя!'}]}
				>
					<Input />
				</Form.Item>
				<Form.Item
					name="lastName"
					label="Фамилия"
					rules={[{required: true, message: 'Пожалуйста, введите фамилию!'}]}
				>
					<Input />
				</Form.Item>
				<Form.Item
					name="role"
					label="Роль"
					rules={[{required: true, message: 'Пожалуйста, выберите роль!'}]}
				>
					<Select>
						<Option value="STUDENT">{'Студент'}</Option>
						<Option value="TEACHER">{'Преподаватель'}</Option>
						<Option value="ADMIN">{'Администратор'}</Option>
					</Select>
				</Form.Item>
				<Form.Item
					name="email"
					label="Email"
					rules={[
						{type: 'email', required: true, message: 'Пожалуйста, введите корректный email!'},
					]}
				>
					<Input />
				</Form.Item>
				{!user && (
					<Form.Item
						name="password"
						label="Пароль"
						rules={[{required: true, message: 'Пожалуйста, введите пароль!'}]}
					>
						<Input.Password />
					</Form.Item>
				)}
				{user && (
					<Form.Item
						name="password"
						label="Пароль (оставьте пустым, если не нужно изменять)"
					>
						<Input.Password />
					</Form.Item>
				)}
				{isTeacher && (
					<Form.Item
						name="subjects" // TODO неправильный ключ или хз
						label="Предметы"
						rules={[{required: true, message: 'Выберите хотя бы один предмет!'}]}
					>
						<Select mode="multiple" placeholder="Выберите предметы">
							{subjects.map(subject => (
								<Option key={subject.subjectId} value={subject.subjectId}>
									{subject.name}
								</Option>
							))}
						</Select>
					</Form.Item>
				)}
				{isStudent && (
					<Form.Item name="groupIds" label="Группы">
						<Select mode="multiple" placeholder="Выберите группы">
							{groups.map(group => (
								<Option key={group.groupId} value={group.groupId} disabled={selectedGroupIds.includes(group.groupId)}>
									{group.name}
								</Option>
							))}
						</Select>
					</Form.Item>
				)}
				<Form.Item label="Аватарка">
					<Upload
						accept="image/png, image/jpeg"
						showUploadList={false}
						beforeUpload={handleFileChange}
					>
						<Button>{'Загрузить изображение'}</Button>
					</Upload>
					{imageData && (
						<img src={imageData} alt="Avatar" style={{marginTop: 16, maxWidth: '100%'}} />
					)}
				</Form.Item>
				<Button type="primary" onClick={handleSubmit} style={{marginTop: 16}}>
					{'Сохранить'}
				</Button>
			</Form>
		</Modal>
	)
}

export {
	UserFormModal,
}
