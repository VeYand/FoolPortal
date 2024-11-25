import {Layout, Menu} from 'antd'
import React, {useState} from 'react'
import styles from './Tabs.module.css'

type TabType = {
	key: string,
	name: string,
	icon: React.ReactNode,
	content: React.ReactNode,
}

type TabsProps = {
	tabs: TabType[],
}

const Tabs = ({tabs}: TabsProps) => {
	const [activeTabKey, setActiveTabKey] = useState(tabs[0]?.key ?? '')

	const handleSelect = ({key}: {key: string}) => {
		setActiveTabKey(key)
	}

	return (
		<Layout className={styles.layout}>
			<Layout.Sider width={200}>
				<Menu
					mode="inline"
					defaultSelectedKeys={[activeTabKey]}
					className={styles.menu}
					items={tabs.map(tab => ({
						key: tab.key,
						icon: tab.icon,
						label: tab.name,
					}))}
					onClick={handleSelect}
				/>
			</Layout.Sider>
			<Layout>
				<Layout.Content className={styles.content}>
					{tabs.find(tab => tab.key === activeTabKey)?.content}
				</Layout.Content>
			</Layout>
		</Layout>
	)
}

export {
	Tabs,
}
